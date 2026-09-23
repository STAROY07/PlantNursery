<!DOCTYPE html>
<html>
<head>
    <title>DOM Manipulation Example</title>

    <script>
        // Function to get contents of fields
        function showData() 
        {
            var name = document.getElementById("name").value;
            var email = document.getElementById("email").value;

            document.getElementById("result").innerHTML =
                "Name: " + name + "<br>Email: " + email;
        }
    </script>
</head>

<body>

    <h2>DOM Manipulation to Get Contents of Fields</h2>

    Name:
    <input type="text" id="name"><br><br>

    Email:
    <input type="text" id="email"><br><br>

    <!-- Call function on click event -->
    <button onclick="showData()">Submit</button>

    <h3>Output:</h3>
    <p id="result"></p>

</body>
</html>