document.addEventListener("DOMContentLoaded", function(event) {
  document.getElementById("name").addEventListener("change", handleFeatureNameChange);
  handleFeatureNameChange();
});

function handleFeatureNameChange() {
  var e = document.getElementById("name");
  var value = e.options[e.selectedIndex].value;

  $.ajax({
    type: 'GET',
    data: {
      name: value,
    },
    url:  baseDir + 'module/productfeatureinformation/ajax',
    success: function(data) {
      var options = "";
      for (var i = 0; i < data.selected.length; i++) {
       options = options + "<option value = \"" + data.selected[i].value + "\">" + data.selected[i].value + "</option>";
      }
      document.getElementById("value").innerHTML = options;
      document.getElementById("value").disabled = false; 
    }
  });
}

