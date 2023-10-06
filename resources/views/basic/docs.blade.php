<!DOCTYPE html>
<html>
<title>HRM Documentation v1.0</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<body>

<div class="w3-sidebar w3-bar-block w3-light-grey w3-card" style="width:130px">
  <h5 class="w3-bar-item">HRM</h5>
  <button class="w3-bar-item w3-button tablink" onclick="openCity(event, 'Indemnity')">Indemnity</button>
  <button class="w3-bar-item w3-button tablink" onclick="openCity(event, 'Salary')">Salary</button>
  <button class="w3-bar-item w3-button tablink" onclick="openCity(event, 'Tokyo')">Tokyo</button>
</div>

<div style="margin-left:130px">
    <div class="w3-padding">HRM Documentation v1.0</div>

    <div id="Indemnity" class="w3-container city" style="display:none">
        <h2>Indemnity</h2>
        <small>By Revathy, 26-08-2023</small>
        <ol>
            <li>
                Less than 3 Years
                <p>Indemnity = 0</p>
            </li>
            <li>
                3 Years to 5 Years
                <p>Min months = 36</p>
                <p>Max months = 60</p>
                <p>Salary = Basic Salary</p>
                <p>Indemnity Amount = 1.25</p>
                <p>Indemnity Percentage = 50%</p>
                <p>Per day Salary = Salary / Common working days in a month</p>
                <p>Formula = ((Per day Salary * Indemnity Amount * Months Worked)/100) * Indemnity Percentage</p>
            </li>
            <li>
                5 Years to 10 Years
                <p>Min months = 60</p>
                <p>Max months = 120</p>
                <p>Salary = Basic Salary</p>
                <p>Indemnity Amount = 2.5</p>
                <p>Indemnity Percentage = 67%</p>
                <p>Per day Salary = Salary / Common working days in a month</p>
                <p>Formula = ((Per day Salary * Indemnity Amount * Months Worked)/100) * Indemnity Percentage</p>
            </li>
            <li>
                10+ Years
                <p>Min months = 120</p>
                <p>Max months = Unlimited</p>
                <p>Salary = Basic Salary</p>
                <p>Indemnity Amount = 2.5</p>
                <p>Indemnity Percentage = 100%</p>
                <p>Per day Salary = Salary / Common working days in a month</p>
                <p>Formula = ((Per day Salary * Indemnity Amount * Months Worked)/100) * Indemnity Percentage</p>
            </li>
        </ol>
    <p></p>
    <p>It is the most populous city in the United Kingdom, with a metropolitan area of over 13 million inhabitants.</p>
  </div>

  <div id="Salary" class="w3-container city" style="display:none">
    <h2>Paris</h2>
    <p>Paris is the capital of France.</p> 
    <p>The Paris area is one of the largest population centers in Europe, with more than 12 million inhabitants.</p>
  </div>

  <div id="Tokyo" class="w3-container city" style="display:none">
    <h2>Tokyo</h2>
    <p>Tokyo is the capital of Japan.</p>
    <p>It is the center of the Greater Tokyo Area, and the most populous metropolitan area in the world.</p>
  </div>

</div>

<script>
function openCity(evt, cityName) {
  var i, x, tablinks;
  x = document.getElementsByClassName("city");
  for (i = 0; i < x.length; i++) {
    x[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablink");
  for (i = 0; i < x.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" w3-red", ""); 
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " w3-red";
}
</script>

</body>
</html>
