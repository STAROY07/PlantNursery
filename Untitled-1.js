/*
Crangle-starter (single-file React component)

Instructions:
1) Create a React app (Vite recommended):
   npx create-react-app crangle-starter
   cd crangle-starter
2) Install dependencies:
   npm install three three-stdlib @react-three/fiber @react-three/drei jspdf
   (You can omit @react-three/* and use raw three if you prefer.)
3) Replace src/App.js with this file's contents (rename to App.js) and run:
   npm start

What this starter includes:
- A Three.js scene inside React using react-three-fiber and drei helpers.
- Add "Crane" (simple group of boxes) and "Site Object" (box) via UI buttons.
- Select + transform (move/rotate/scale) of objects.
- Save / Load project to localStorage (JSON).
- Export a PNG snapshot and a simple PDF export using jsPDF.

This is a minimal, extendable starting point for a Crangle-like web app.
*/

import React, { useRef, useState, useEffect } from "react";
import { createRoot } from 'react-dom/client';
import { Canvas, useThree, useFrame } from "@react-three/fiber";
import { OrbitControls, TransformControls, Html } from "@react-three/drei";
import * as THREE from "three";
import { jsPDF } from "jspdf";

function Grid() {
  return (
    <mesh rotation={[-Math.PI / 2, 0, 0]} receiveShadow>
      <planeBufferGeometry args={[200, 200]} />
      <meshStandardMaterial color="#f3f3f3" />
    </mesh>
  );
}

function Crane({ obj, isSelected, onPointerDown }) {
  // Simple crane representation: base + boom
  return (
    <group position={obj.position} rotation={obj.rotation} scale={obj.scale}>
      <mesh onPointerDown={onPointerDown} castShadow>
        <boxBufferGeometry args={[1.5, 0.5, 1.5]} />
        <meshStandardMaterial color={isSelected ? "orange" : "#2e86de"} />
      </mesh>
      <mesh position={[0, 1.2, 0]} castShadow>
        <boxBufferGeometry args={[0.3, 2.4, 0.3]} />
        <meshStandardMaterial color="#555" />
      </mesh>
      <mesh position={[0, 2.6, 0.9]} rotation={[0, 0, -0.2]} castShadow>
        <boxBufferGeometry args={[0.2, 0.2, 3]} />
        <meshStandardMaterial color="#777" />
      </mesh>
    </group>
  );
}

function SiteObject({ obj, isSelected, onPointerDown }) {
  return (
    <group position={obj.position} rotation={obj.rotation} scale={obj.scale}>
      <mesh onPointerDown={onPointerDown} castShadow>
        <boxBufferGeometry args={[1, 1, 1]} />
        <meshStandardMaterial color={isSelected ? "orange" : "#27ae60"} />
      </mesh>
    </group>
  );
}

function Scene({ objects, setObjects, selectedId, setSelectedId }) {
  const transformRef = useRef();
  const { camera, gl, scene } = useThree();
  const [mode, setMode] = useState("translate");

  useEffect(() => {
    if (!transformRef.current) return;
    transformRef.current.setMode(mode);
  }, [mode, selectedId]);

  // Apply transform changes to object list
  useFrame(() => {
    if (!transformRef.current) return;
    const ctrl = transformRef.current;
    if (!ctrl.object) return;
    const uuid = ctrl.object.userData._id;
    setObjects((prev) => prev.map((o) => (o.id === uuid ? {
      ...o,
      position: [ctrl.object.position.x, ctrl.object.position.y, ctrl.object.position.z],
      rotation: [ctrl.object.rotation.x, ctrl.object.rotation.y, ctrl.object.rotation.z],
      scale: [ctrl.object.scale.x, ctrl.object.scale.y, ctrl.object.scale.z]
    } : o)));
  });

  return (
    <>
      <ambientLight intensity={0.6} />
      <directionalLight position={[5, 10, 5]} intensity={0.8} castShadow />
      <Grid />
      <OrbitControls makeDefault />

      {objects.map((o) => (
        o.type === "crane" ? (
          <Crane key={o.id} obj={o} isSelected={selectedId === o.id} onPointerDown={(e) => { e.stopPropagation(); setSelectedId(o.id); }} />
        ) : (
          <SiteObject key={o.id} obj={o} isSelected={selectedId === o.id} onPointerDown={(e) => { e.stopPropagation(); setSelectedId(o.id); }} />
        )
      ))}

      {/* Transform Controls attach to a temporary mesh representing selected object */}
      <TransformControls ref={transformRef} object={null} mode={mode} />

      {/* Helper that attaches the TransformControls when selectedId changes */}
      <AttachTransform selectedId={selectedId} objects={objects} transformRef={transformRef} />
    </>
  );
}

function AttachTransform({ selectedId, objects, transformRef }) {
  const { scene } = useThree();

  useEffect(() => {
    if (!selectedId) {
      if (transformRef.current) transformRef.current.detach();
      return;
    }
    // Find the mesh in the scene by userData._id
    const found = scene.getObjectByProperty('userData', undefined);
    // We'll attach by searching recursively for the child where userData._id === selectedId
    function findById(obj) {
      if (obj.userData && obj.userData._id === selectedId) return obj;
      for (let i = 0; i < obj.children.length; i++) {
        const f = findById(obj.children[i]);
        if (f) return f;
      }
      return null;
    }

    // Attach: because our components create fresh meshes, easiest approach is to create a new invisible helper and attach it to scene
    // Here, we create a small box mesh and place it at the selected object's transform and attach TransformControls to it.

    if (!selectedId) return;

    // Remove old helper
    const old = scene.getObjectByName("__crangle_helper");
    if (old) scene.remove(old);

    // Find object transform from objects array
    const obj = objects.find(o => o.id === selectedId);
    if (!obj) return;

    const helper = new THREE.Object3D();
    helper.name = "__crangle_helper";
    helper.position.fromArray(obj.position);
    helper.rotation.fromArray(obj.rotation);
    helper.scale.fromArray(obj.scale);
    helper.userData._id = selectedId;
    scene.add(helper);

    if (transformRef.current) transformRef.current.attach(helper);

    return () => {
      if (transformRef.current) transformRef.current.detach();
      const rem = scene.getObjectByName("__crangle_helper");
      if (rem) scene.remove(rem);
    };

  }, [selectedId, objects, scene, transformRef]);

  return null;
}

function Toolbar({ addCrane, addObject, saveProject, loadProject, exportPNG, exportPDF, setMode }) {
  return (
    <div style={{ position: 'absolute', left: 10, top: 10, zIndex: 10, background: 'rgba(255,255,255,0.9)', padding: 12, borderRadius: 8 }}>
      <div style={{ marginBottom: 8 }}>
        <button onClick={addCrane}>Add Crane</button>
        <button onClick={addObject} style={{ marginLeft: 6 }}>Add Object</button>
      </div>
      <div style={{ marginBottom: 8 }}>
        <button onClick={() => setMode('translate')}>Move</button>
        <button onClick={() => setMode('rotate')} style={{ marginLeft: 6 }}>Rotate</button>
        <button onClick={() => setMode('scale')} style={{ marginLeft: 6 }}>Scale</button>
      </div>
      <div style={{ marginBottom: 8 }}>
        <button onClick={saveProject}>Save</button>
        <button onClick={loadProject} style={{ marginLeft: 6 }}>Load</button>
      </div>
      <div>
        <button onClick={exportPNG}>Export PNG</button>
        <button onClick={exportPDF} style={{ marginLeft: 6 }}>Export PDF</button>
      </div>
    </div>
  );
}

function App() {
  const [objects, setObjects] = useState(() => {
    // default example
    return [
      { id: 'crane-1', type: 'crane', position: [0, 0, 0], rotation: [0, 0, 0], scale: [1, 1, 1] }
    ];
  });
  const [selectedId, setSelectedId] = useState(null);
  const canvasRef = useRef();

  function addCrane() {
    const id = `crane-${Date.now()}`;
    setObjects(prev => [...prev, { id, type: 'crane', position: [Math.random()*4-2, 0, Math.random()*4-2], rotation: [0,0,0], scale: [1,1,1] }]);
  }

  function addObject() {
    const id = `obj-${Date.now()}`;
    setObjects(prev => [...prev, { id, type: 'object', position: [Math.random()*6-3, 0.5, Math.random()*6-3], rotation: [0,0,0], scale: [1,1,1] }]);
  }

  function saveProject() {
    const payload = { objects };
    localStorage.setItem('crangle_project', JSON.stringify(payload));
    alert('Project saved to localStorage');
  }

  function loadProject() {
    const raw = localStorage.getItem('crangle_project');
    if (!raw) return alert('No saved project');
    try {
      const parsed = JSON.parse(raw);
      if (parsed.objects) setObjects(parsed.objects);
    } catch (e) {
      alert('Invalid project data');
    }
  }

  async function exportPNG() {
    // grab canvas and convert to PNG
    const canvas = document.querySelector('canvas');
    if (!canvas) return;
    const data = canvas.toDataURL('image/png');
    const a = document.createElement('a');
    a.href = data;
    a.download = 'crangle_scene.png';
    a.click();
  }

  function exportPDF() {
    const canvas = document.querySelector('canvas');
    if (!canvas) return;
    const data = canvas.toDataURL('image/png');
    const pdf = new jsPDF({ unit: 'px', format: [canvas.width, canvas.height] });
    pdf.addImage(data, 'PNG', 0, 0, canvas.width, canvas.height);
    pdf.save('crangle_scene.pdf');
  }

  return (
    <div style={{ width: '100vw', height: '100vh' }}>
      <Toolbar addCrane={addCrane} addObject={addObject} saveProject={saveProject} loadProject={loadProject} exportPNG={exportPNG} exportPDF={exportPDF} setMode={(m) => {
        // broadcast transform mode change by dispatching a custom event so Scene can pick it up
        const e = new CustomEvent('crangle:setMode', { detail: m });
        window.dispatchEvent(e);
        // Also set local storage for quick state
      }} />

      <Canvas shadows camera={{ position: [6, 6, 6], fov: 50 }} ref={canvasRef} onPointerMissed={() => setSelectedId(null)}>
        <Scene objects={objects} setObjects={setObjects} selectedId={selectedId} setSelectedId={setSelectedId} />
      </Canvas>
    </div>
  );
}

// Mount
const container = document.getElementById('root');
if (container) {
  const root = createRoot(container);
  root.render(<App />);
}

export default App;
