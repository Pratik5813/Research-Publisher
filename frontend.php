<?php
function blockAFrontend($attributes)
{
  // Check if the search bar has already been rendered
  static $searchBarRendered = false;

  // Render the search bar only if it hasn't been rendered yet
  if (!$searchBarRendered) {
    // Render the search bar HTML here
    $searchBarHTML = '<div class="input-group mb-5">
    <input type="text" class="form-control pp_search_form" placeholder="Search here..." aria-label="Search here...">
    <button class="btn pp_search_button" type="button">Search</button>
  </div>';

    // Set the flag to indicate that the search bar has been rendered
    $searchBarRendered = true;
  } else {
    // If the search bar has already been rendered, set the HTML to an empty string
    $searchBarHTML = '';
  }
  //Check if 'researchTitle' attribute is set and not empty
  if (isset($attributes['researchTitle']) && !empty($attributes['researchTitle'])) {
    // Parse and format the published date
    $researchTitle = $attributes['researchTitle'];
  } else {
    // Set the default value for $publishedDate
    $researchTitle = " ";
  }

  //Check if 'downloadLink' attribute is set and not empty
  if (isset($attributes['downloadLink']) && !empty($attributes['downloadLink'])) {
    $downloadLink = $attributes['downloadLink'];
  } else {
    $downloadLink = " ";
  }
  //Check if 'abstractLink' attribute is set and not empty
  if (isset($attributes['abstractLink']) && !empty($attributes['abstractLink'])) {
    $abstractLink = $attributes['abstractLink'];
  } else {
    $abstractLink = " ";
  }
  //Check if 'doiNumber' attribute is set and not empty
  if (isset($attributes['doiNumber']) && !empty($attributes['doiNumber'])) {
    $doiNumber = $attributes['doiNumber'];
  } else {
    $doiNumber = " ";
  }

  // Check if the 'publishedDate' attribute is set and not empty
  if (isset($attributes['publishedDate']) && !empty($attributes['publishedDate'])) {
    // Parse and format the published date
    $publishedDate = date_format(date_create($attributes['publishedDate']), 'd/m/Y');
  } else {
    // Set the default value for $publishedDate
    $publishedDate = "Published date not found";
  }

  // Mapping authors array to buttons with user icon
  $authorsButtons = '';
  if (isset($attributes['authors']) && is_string($attributes['authors'])) {
    // Split the authors string into an array using comma as the delimiter
    $authorsArray = explode(',', $attributes['authors']);
    foreach ($authorsArray as $author) {
      // Trim each author to remove leading/trailing whitespace
      $author = trim($author);
      $authorsButtons .= '<button class="authorButtons disabled mb-2"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill" viewBox="0 0 16 16">
              <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
          </svg> ' . $author . '</button>';
    }
  }
  $ppRightStyle = isset($attributes['authorImages']) && !empty($attributes['authorImages']) ? '' : 'display: none;';

  // Prepare auto-slider HTML for author images
  $authorImagesHTML = '';
  if (isset($attributes['authorImages']) && is_array($attributes['authorImages'])) {
    foreach ($attributes['authorImages'] as $index => $image) {
      $class = ($index == 0) ? 'pp_active' : ''; // Add pp_active class to the first image
      $authorImagesHTML .= '<img src="' . $image['url'] . '" alt="' . $image['alt'] . '" class="' . $class . '" style="width: 95px; height: 95px;">';
    }
  }

  $plugin_base_url = plugin_dir_url(__FILE__);

  // Heredoc string incorporating the mapped authors buttons
  return <<<HTML
<html>
<body>
  $searchBarHTML
    <div class="card pp_research_block" style="width: 100%;">
        <span class="position-absolute top-0 end-0 translate-middle-y badge p-2 fw-normal pp_published_on">
            Published on: $publishedDate
        </span>
        <div class="card-body">
            <h5 class="card-title my-1">$researchTitle</h5>
            <div class="authors my-1">
                {$authorsButtons}
            </div>
            <div class="d-flex flex-column flex-sm-row justify-content-between">
              <div class="pp_left d-flex">
              <div class="my-1 pp_doi_box me-4">
              <img class="pp_doi_img" src="{$plugin_base_url}inc/images/doi.png"><span class="pp_doi_number">$doiNumber</span><a href="https://doi.org/{$doiNumber}" target="_blank" class="pp_doi_button">Verify DOI</a>
              </div>
              <div class="otherLinks my-1">
                <a class="pp_abstract" href="{$abstractLink}" target="_blank"><img src="{$plugin_base_url}inc/images/abstract.png">View Abstract</a>
                <a class="pp_download" href="{$downloadLink}" target="_blank"><img src="{$plugin_base_url}inc/images/adobe.png">Download</a>
              </div>
              </div>
              <div class="pp_right" style="{$ppRightStyle}">
              <div class="auto-slider-container">
                    <div class="auto-slider">
                       {$authorImagesHTML}
                    </div>
                    <button class="prev" onclick="prevSlide(this)">&#10094;</button>
                    <button class="next" onclick="nextSlide(this)">&#10095;</button>
                </div>
              </div>           
            </div>
        </div>
    </div>
    <style>
    /* Search Bar */
    .pp_search_form:focus{
      box-shadow:none !important;
      border: 1px solid #ced4da !important;
    }
    .pp_search_button{
      background-color:#507acb;
      color: #fff;
      font-family: 'Aptos', sans-serif;
    }
    .pp_search_button:hover, .pp_search_button:active, .pp_search_button:focus{
      background-color:#507acb !important;
      color:#fff !important;
      box-shadow: none !important;
    }
    .pp_research_block{
      font-family: 'Aptos', sans-serif;
      border:2px solid rgba(0,0,0,.125) !important;
      border-radius:5px;
      margin-bottom:50px;
    }
    .pp_research_block h5{
      font-family: 'Aptos', sans-serif;
      font-size:14px;
      font-weight:700;
      color:#1f4e79;
    }
    .authorButtons {
        background-color: #a6a6a6;
        margin-right: 10px;
        font-family: 'Aptos', sans-serif;
        font-size:12px;
        border:none;
        color:#fff;
        padding:3px;
        padding-right:5px;
        cursor: default !important;
        border-radius:3px;
    }
    .authorButtons svg {
        width:10px;
        height:10px;
        margin-bottom:2px;
    }
    .pp_published_on{
      background-color:#c65a09;
      font-size:10px;
      font-family: 'Aptos', sans-serif;
      border-radius: 0;
      margin-right:15px;
    }
    .pp_doi_box{
      height:30px !important;
      box-shadow: rgba(0, 0, 0, 0.24) 0px 3px 8px;
      border-radius:3px
    }
    .pp_doi_number{
      color:#1ba8ec;
      font-size:12px;
      margin-right:5px;
      font-weight:700;
    }
    .pp_doi_button{
      padding:8px;
      text-decoration:none;
      font-family:'Aptos', sans-serif;
      font-size:12px;
      font-weight:700;
      background-color:#fbc400;
      color:#fff;
      border-radius:3px
    }
    .pp_doi_button:hover, .pp_doi_button:active, .pp_doi_button:visited, .pp_doi_button:focus{
      color:#fff !important;
    }
    .pp_doi_img{
      width:30px;
      margin-right:3px;
    }
    .pp_abstract{
      text-decoration:none;
      background-color:#507acb;
      font-family: 'Aptos',sans-serif;
      font-size:12px;
      font-weight:700;
      padding: 6px;
      color:#fff;
      border-radius:3px;
    }
    .pp_abstract:hover, .pp_abstract:active, .pp_abstract:visited, .pp_abstract:focus{
      color:#fff !important;
    }
    .pp_abstract img{
      width:14px;
      margin-bottom:2px;
      margin-right:3px;
    }
    .pp_download{
      text-decoration:none;
      background-color:#548235;
      font-family: 'Aptos',sans-serif;
      font-size:12px;
      font-weight:700;
      padding: 6px;
      color:#fff;
      border-radius:3px;
    }
    .pp_download:hover, .pp_download:active, .pp_download:visited, .pp_download:focus{
      color:#fff !important;
    }
    .pp_download img{
      width:14px;
      margin-bottom:2px;
      margin-right:3px;
    }
    .pp_right{
      margin-top:-35px;
      margin-right:30px
    }

    /* Slider Styles */

    .auto-slider-container {
    position: relative;
    width:100px;
    height:100px;
    margin: auto; /* Centering the auto-slider */
    z-index: 0;
}

.auto-slider-container .prev,
.auto-slider-container .next {
    cursor: pointer;
    position: absolute;
    top: 35%;
    width: 30px;
    color: #507acb;
    font-weight: bold;
    font-size: 18px;
    user-select: none;
    background:none;
    border:none;
    z-index: 999;
}
.auto-slider-container .prev {
    left: -30px; /* Positioning prev button to the left */
}

.auto-slider-container .next {
    right: -25px; /* Positioning next button to the right */
}
.auto-slider img {
    position: absolute;
    top: 0;
    left: 50%; /* Initially position the image outside the container on the right */
    width: 100%; /* Adjust the width to fit the container */
    height: auto; /* Maintain the aspect ratio */
    opacity: 0; /* Initially hide images */
    transition: opacity 0.5s cubic-bezier(0.77, 0, 0.175, 1), left 0.5s cubic-bezier(0.77, 0, 0.175, 1); /* Use cubic-bezier timing function for opacity and left properties */
}

.auto-slider img.pp_active {
    z-index: 999;
    left: 0; /* Slide active image from right to left */
    opacity: 1; /* Make the active image fully visible */
}

    </style>

<script>
  // Add event listener to the search button
  document.querySelector(".pp_search_button").addEventListener("click", function () {
    // Get the search query from the input field
    var searchQuery = document.querySelector(".pp_search_form").value.trim().toLowerCase()

    // Get all research blocks
    var researchBlocks = document.querySelectorAll(".pp_research_block")

    // Loop through each research block
    researchBlocks.forEach(function (block) {
      // Get the research title and author names from the block
      var title = block.querySelector(".card-title").textContent.trim().toLowerCase()
      var authors = block.querySelector(".authors").textContent.trim().toLowerCase()
      var doiNumber = block.querySelector(".pp_doi_number").textContent.trim().toLowerCase()

      // Check if the search query matches the title or authors
      if (title.includes(searchQuery) || authors.includes(searchQuery) || doiNumber.includes(searchQuery)) {
        // If there is a match, display the block
        block.style.display = "block"
      } else {
        // If there is no match, hide the block
        block.style.display = "none"
      }
    })
  })

  function showSlides(container) {
    var images = container.querySelectorAll(".auto-slider img")
    var numImages = images.length
    var slideIndex = numImages - 1 // Start from the last image

    function show() {
      // Ensure images array is not empty
      if (images.length === 0) {
        return
      }

      // Remove pp_active class from all images
      images.forEach(function (img) {
        if (img) {
          img.classList.remove("pp_active")
        }
      })

      // Set pp_active class for the active image if it exists
      if (images[slideIndex]) {
        images[slideIndex].classList.add("pp_active")
      }

      slideIndex--
      if (slideIndex < 0) {
        slideIndex = numImages - 1 // Reset to the last image index
      }

      // Hide arrows if there is only one image
      if (numImages <= 1) {
        container.querySelector(".prev").style.display = "none"
        container.querySelector(".next").style.display = "none"
      } else {
        container.querySelector(".prev").style.display = "block"
        container.querySelector(".next").style.display = "block"
      }
    }

    // Call show function immediately to display the initial slide
    show()

    // Store the interval ID in the container element and setup interval
    container.interval = setInterval(function () {
      show() // Call show function every 4 seconds
    }, 4000)
  }

  function prevSlide(button) {
    var container = button.closest(".auto-slider-container")
    var images = container.querySelectorAll(".auto-slider img")
    var numImages = images.length

    // Find the index of the currently active slide
    var currentIndex = Array.from(images).findIndex((img) => img.classList.contains("pp_active"))

    // Remove pp_active class from the current slide
    images[currentIndex].classList.remove("pp_active")

    // Calculate the index of the previous slide
    var prevIndex = (currentIndex - 1 + numImages) % numImages

    // Add pp_active class to the previous slide
    images[prevIndex].classList.add("pp_active")
  }

  function nextSlide(button) {
    var container = button.closest(".auto-slider-container")
    var images = container.querySelectorAll(".auto-slider img")
    var numImages = images.length

    // Find the index of the currently active slide
    var currentIndex = Array.from(images).findIndex((img) => img.classList.contains("pp_active"))

    // Remove pp_active class from the current slide
    images[currentIndex].classList.remove("pp_active")

    // Calculate the index of the next slide
    var nextIndex = (currentIndex + 1) % numImages

    // Add pp_active class to the next slide
    images[nextIndex].classList.add("pp_active")
  }

  // Call showSlides function when the page loads
  document.addEventListener("DOMContentLoaded", function () {
    var containers = document.querySelectorAll(".auto-slider-container")
    containers.forEach(function (container) {
      showSlides(container)
    })
  })
</script>

</body>
</html>
HTML;
}
