var currentTab = 0;
showTab(currentTab);

function showTab(n) {
    var x = document.getElementsByClassName("tab");
    x[n].style.display = "block";

    document.getElementById("prevBtn").style.display = n === 0 ? "none" : "inline";
    document.getElementById("nextBtn").innerHTML = n === (x.length - 1) ? "Invia" : "Successivo &#8594;";

    fixStepIndicator(n);
}

function nextPrev(n) {
    var x = document.getElementsByClassName("tab");
    x[currentTab].style.display = "none";
    currentTab += n;

    if (currentTab >= x.length) {
        document.getElementById("multi_step_form").submit();
        return false;
    }
    showTab(currentTab);
}

function fixStepIndicator(n) {
    var i, x = document.getElementsByClassName("step");
    for (i = 0; i < x.length; i++) {
        x[i].className = x[i].className.replace(" active", "").replace(" finish", "");
        if (i < n) x[i].className += " finish";
    }
    x[n].className += " active";
}
