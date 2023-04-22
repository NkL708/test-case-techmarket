function makeRequest(buttonId) {
    var xhr = XMLHttpRequest()
    xhr.open("POST", "tree.php", true)
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded")
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {

        }
    }
    document.getElementById(buttonId).addEventListener("click", function () {
        xhr.send()
    })
}

makeRequest("one")
makeRequest("many")
makeRequest("delete")
