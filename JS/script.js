window.onload = function () {
    document.getElementById('calcard').style.border = "1px solid black";

    document.getElementById('calcard').style.background = "#FFFFE0";
}
function changeDivColor(e) {



    // get the image element

    //   var img = document.getElementsByTagName("img")[0];

    var card = e.getAttribute("data-img");

    var img = e;



    //create a canvas element and draw the image on it

    var canvas = document.createElement("canvas");

    canvas.width = img.width;

    canvas.height = img.height;

    var context = canvas.getContext("2d");

    context.drawImage(img, 0, 0);



    // get the average color of the image

    var pixelData = context.getImageData(0, 0, canvas.width, canvas.height).data;

    var r = 0,

        g = 0,

        b = 0;

    var count = 0;



    for (var i = 0; i < pixelData.length; i += 4) {

        r += pixelData[i];

        g += pixelData[i + 1];

        b += pixelData[i + 2];

        count++;



    }

    // var div = document.getElementsByClassName(e);  

    // for(var i=0; i<=div.length;i++){   

    var avgR = Math.round(r / count);

    var avgG = Math.round(g / count);

    var avgB = Math.round(b / count);

    var div = document.getElementById(`cardcal_${card}`)

    div.style.backgroundColor = "rgb(" + avgR + "," + avgG + "," + avgB + ")";



    // }



    //set the background color of the div

    var div = document.getElementsByClassName("cardcal");





}
changeDivColor();

function setTextColorBasedOnBackground() {
    var cards = document.getElementsByClassName("cardcal");
  
    for (var i = 0; i < cards.length; i++) {
      var card = cards[i];
      var backgroundColor = getComputedStyle(card).backgroundColor;
  
      if (isDarkColor(backgroundColor)) {
        card.style.color = "white";
      } else if (isGreyColor(backgroundColor) || isWhiteColor(backgroundColor) || isYellowColor(backgroundColor)) {
        card.style.color = "black";
      }
    }
  }
  
  function isDarkColor(color) {
    var luminance = calculateLuminance(color);
    return luminance < 0.5;
  }
  
  function isGreyColor(color) {
    var rgb = getRGB(color);
    var threshold = 20;
    return Math.abs(rgb.r - rgb.g) < threshold && Math.abs(rgb.g - rgb.b) < threshold && Math.abs(rgb.b - rgb.r) < threshold;
  }
  
  function isWhiteColor(color) {
    return color === "white";
  }
  
  function isYellowColor(color) {
    return color === "yellow";
  }
  
  function calculateLuminance(color) {
    var rgb = hexToRGB(color);
    return (0.299 * rgb.r + 0.587 * rgb.g + 0.114 * rgb.b) / 255;
  }
  
  function getRGB(color) {
    var regex = /^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/;
    var matches = color.match(regex);
  
    if (matches) {
      return {
        r: parseInt(matches[1], 10),
        g: parseInt(matches[2], 10),
        b: parseInt(matches[3], 10)
      };
    }
  
    return null;
  }
  
  function hexToRGB(color) {
    var regex = /^#?([a-f\d]{2})([a-f\d]{2})([a-f\d]{2})$/i;
    var matches = color.match(regex);
  
    if (matches) {
      return {
        r: parseInt(matches[1], 16),
        g: parseInt(matches[2], 16),
        b: parseInt(matches[3], 16)
      };
    }
  
    return null;
  }
  
  setTextColorBasedOnBackground();
  
  


