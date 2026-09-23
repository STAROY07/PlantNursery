<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQs | Help Center</title>

<style>
body{
    font-family: Arial, sans-serif;
    background:#f4fff4;
    margin:0;
    padding:0;
}

.faq-container{
    max-width:900px;
    margin:40px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
    box-shadow:0 0 10px rgba(0,0,0,0.1);
}

.faq-title{
    text-align:center;
    color:green;
    margin-bottom:25px;
}

.faq-item{
    border-bottom:1px solid #ddd;
}

.faq-question{
    width:100%;
    background:none;
    border:none;
    padding:15px;
    font-size:16px;
    font-weight:bold;
    text-align:left;
    cursor:pointer;
    color:#006400;
}

.faq-question:hover{
    background:#f0fff0;
}

.faq-answer{
    display:none;
    padding:0 15px 15px;
    color:#333;
    line-height:1.6;
}

.back-link{
    display:inline-block;
    margin-top:20px;
    text-decoration:none;
    color:green;
    font-weight:bold;
}
</style>
</head>

<body>

<div class="faq-container">
    <h1 class="faq-title">Frequently Asked Questions</h1>

    <div class="faq-item">
        <button class="faq-question">Q1. How do I order plants?</button>
        <div class="faq-answer">
            Select a plant → Add to Cart → Proceed to Checkout → Place Order.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q2. What payment methods are available?</button>
        <div class="faq-answer">
            We accept Cash on Delivery and Online Payments.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q3. How long does delivery take?</button>
        <div class="faq-answer">
            Delivery usually takes 2 to 5 working days depending on location.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q4. What if my plant arrives damaged?</button>
        <div class="faq-answer">
            You can request a replacement within 24 hours of delivery with proof.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q5. Can I cancel my order?</button>
        <div class="faq-answer">
            Yes, orders can be cancelled before they are shipped.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q6. Do you provide plant care instructions?</button>
        <div class="faq-answer">
            Yes, every plant comes with basic care instructions and tips.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q7. Are the plants safe for home use?</button>
        <div class="faq-answer">
            Yes, all our plants are grown naturally and are safe for indoor and outdoor use.
        </div>
    </div>

    <div class="faq-item">
        <button class="faq-question">Q8. How can I contact customer support?</button>
        <div class="faq-answer">
            You can contact us through the Contact Support page in the Help Center.
        </div>
    </div>

    <a class="back-link" href="help_center.php">← Back to Help Center</a>
</div>

<script>
const questions = document.querySelectorAll(".faq-question");

questions.forEach(q => {
    q.addEventListener("click", () => {
        const answer = q.nextElementSibling;
        answer.style.display = answer.style.display === "block" ? "none" : "block";
    });
});
</script>

</body>
</html>
