
<?php
// upkarya.php (this is the page that displays the form)
session_start(); // Start the session at the very beginning

// Ensure db.php is correctly included. Adjust path if necessary.
// Assuming db.php is in OnVerse/php/db.php and upkarya.php is in OnVerse/HTML/upkarya.php
include '../php/db.php'; 

$work_id = $_GET['work_id'] ?? null; // Get work_id from URL parameter
$work_title = 'Upload Chapter'; // Default title

// Fetch the work title based on work_id if provided
if ($work_id) {
    try {
        $stmt = $pdo->prepare("SELECT title FROM works WHERE work_id = ?");
        $stmt->execute([$work_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $work_title = htmlspecialchars($result['title']); // Sanitize for HTML output
        } else {
            // Work not found, optionally set a message or redirect
            error_log("Work with ID " . $work_id . " not found for chapter upload form.");
        }
    } catch (PDOException $e) {
        // Log the error but don't stop page load; use default title
        error_log("Error fetching work title in upkarya.php: " . $e->getMessage());
    }
} else {
    // If no work_id is provided, you might want to redirect to a page to select a work,
    // or display an error to the user. For now, it will just show "Upload Chapter".
}
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ONVERSE - Upload Chapter</title>
    <link
      href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet"
    />
    <style>
      /* Your CSS remains the same */
      body {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f0f2f5;
        margin: 0;
        padding: 20px;
        box-sizing: border-box;
      }

      .buatkarya-container {
        background-color: white;
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0, 56, 180, 0.3);
        width: 1200px;
        padding: 30px;
        text-align: center;
      }

      .logo-container {
        margin-bottom: 20px;
      }

      .logo-text img {
        width: 250px; /* Sesuaikan ukuran logo */
        height: auto;
      }

      .buatkarya-title {
        font-size: 30px;
        font-weight: bold;
        margin-bottom: 30px;
        color: #333;
      }

      .buatkarya-form {
        display: grid;
        grid-template-columns: 1fr;
        gap: 15px 20px;
        margin-bottom: 30px;
        text-align: left;
      }

      .form-group {
        display: flex;
        flex-direction: column;
      }

      .form-label {
        font-size: 14px;
        color: #333;
        margin-bottom: 5px;
      }

      .form-input {
        padding: 10px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 14px;
      }

      .form-input:focus {
        outline: none;
        border-color: #4285f4;
      }

      .file-upload-section {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 5px;
        border: 1px solid #e0e0e0;
      }

      .file-upload-btn {
        background: #0038b4;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 5px;
        font-size: 14px;
        cursor: pointer;
        margin-right: 10px;
        transition: background-color 0.3s ease;
      }

      .file-upload-btn:hover {
        background: #052d6c;
      }

      .clear-all-btn:hover {
        background: #5a020b;
      }

      .file-info {
        font-size: 12px;
        color: #666;
        margin-top: 8px;
      }

      .save-draft-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 12px;
        cursor: pointer;
        margin-top: 10px;
        transition: background-color 0.3s ease;
      }

      .save-draft-btn:hover {
        background: #545b62;
      }

      .uploaded-images {
        margin-top: 15px;
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        padding: 10px;
        background: white;
      }

      .image-item:last-child {
        border-bottom: none;
        margin-bottom: 0;
      }

      .image-preview {
        width: 40px;
        height: 40px;
        object-fit: cover;
        border-radius: 4px;
        margin-right: 10px;
      }

      .image-info {
        flex: 1;
        text-align: left;
      }

      .image-name {
        font-size: 12px;
        font-weight: bold;
        color: #333;
      }

      .image-size {
        font-size: 10px;
        color: #666;
      }

      .remove-image-btn {
        background: #dc3545;
        color: white;
        border: none;
        padding: 4px 8px;
        border-radius: 3px;
        font-size: 10px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      .remove-image-btn:hover {
        background: #c82333;
      }

      .images-counter {
        font-size: 12px;
        color: #4285f4;
        font-weight: bold;
        margin-bottom: 10px;
      }

      .radio-group {
        display: flex;
        gap: 20px;
        align-items: center;
      }

      .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
      }

      .radio-input {
        width: 18px;
        height: 18px;
        border: 2px solid #ddd;
        border-radius: 50%;
        position: relative;
        cursor: pointer;
      }

      .radio-input.checked {
        border-color: #0038b4;
        background: #0038b4;
      }

      .radio-input.checked::after {
        content: "";
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 8px;
        height: 8px;
        background: white;
        border-radius: 50%;
      }

      .schedule-inputs {
        display: flex;
        gap: 10px;
        margin-top: 10px;
      }

      .schedule-input {
        flex: 1;
        padding: 8px;
        border: 1px solid #e0e0e0;
        border-radius: 5px;
        font-size: 12px;
      }

      .buatkarya-buttons {
        display: flex;
        justify-content: center;
        gap: 100px;
        margin-top: 20px;
      }

      .kembali-btn,
      .signup-submit-btn {
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }

      .kembali-btn {
        background-color: #6c757d;
        color: white;
      }

      .kembali-btn:hover {
        background-color: #d0d0d0;
      }

      .signup-submit-btn {
        background-color: #6c757d;
        color: white;
      }

      .signup-submit-btn:hover {
        background-color: #0038b4;
      }

      .hidden {
        display: none;
      }
    </style>
  </head>
  <body class="auth-page">
    <div class="buatkarya-container">
      <div class="logo-container">
        <div class="logo-text">
          <img src="../IMG/judul.png" alt="ONVERSE" />
        </div>
      </div>

      <div class="buatkarya-title" id="chapter-form-title"><?php echo $work_title; ?></div>
      <form
        id="chapterUploadForm"
        action="" method="POST" enctype="multipart/form-data"
      >
        <input type="hidden" name="work_id" id="work_id" value="<?php echo htmlspecialchars($work_id); ?>" />
        <div class="buatkarya-form">
          <div class="form-group">
            <label class="form-label">Chapter Title</label>
            <input
              type="text"
              class="form-input"
              name="chapter_title"
              placeholder="Masukkan judul chapter"
              required
            />
          </div>

          <div class="form-group" style="grid-column: 1">
            <label class="form-label">Upload Chapter Pages</label>
            <div class="file-upload-section">
              <button
                type="button"
                class="file-upload-btn"
                onclick="document.getElementById('chapter-images').click()"
              >
                Select Images
              </button>
              <button
                type="button"
                class="clear-all-btn"
                onclick="clearAllImages()"
              >
                Delete All Images
              </button>
              <div class="file-info">
                *Only PNG, JPG, JPEG files are accepted (multiple allowed) </div>
              <div class="images-counter" id="images-counter">
                0 images selected
              </div>
              <div
                class="uploaded-images"
                id="uploaded-images"
                style="display: none"
              ></div>
            </div>
            <input
              type="file"
              id="chapter-images"
              name="chapter_images[]"
              style="display: none"
              accept=".png, .jpg, .jpeg" multiple
            />
          </div>

          <div class="form-group" style="grid-column: 1">
            <label class="form-label">Comments</label>
            <div class="radio-group">
              <div class="radio-option" onclick="toggleRadio('comments', 'on')">
                <div class="radio-input checked" id="comments-on"></div>
                <span>On</span>
                <input
                  type="radio"
                  name="comments_status"
                  value="on"
                  checked
                  class="hidden-radio"
                />
              </div>
              <div
                class="radio-option"
                onclick="toggleRadio('comments', 'off')"
              >
                <div class="radio-input" id="comments-off"></div>
                <span>Off</span>
                <input
                  type="radio"
                  name="comments_status"
                  value="off"
                  class="hidden-radio"
                />
              </div>
            </div>
          </div>

          <div class="form-group" style="grid-column: 1">
            <label class="form-label">Publish</label>
            <div class="radio-group">
              <div class="radio-option" onclick="toggleRadio('publish', 'now')">
                <div class="radio-input" id="publish-now"></div>
                <span>Now</span>
                <input
                  type="radio"
                  name="publish_type"
                  value="now"
                  class="hidden-radio"
                />
              </div>
              <div
                class="radio-option"
                onclick="toggleRadio('publish', 'schedule')"
              >
                <div class="radio-input checked" id="publish-schedule"></div>
                <span>Schedule</span>
                <input
                  type="radio"
                  name="publish_type"
                  value="schedule"
                  checked
                  class="hidden-radio"
                />
              </div>
            </div>
            <div class="schedule-inputs" id="schedule-fields">
              <input type="date" class="schedule-input" name="schedule_date" />
              <input type="time" class="schedule-input" name="schedule_time" />
              <input
                type="number"
                class="schedule-input"
                name="schedule_minute"
                placeholder="Minute (0-59)"
                min="0"
                max="59"
                value="00"
              />
            </div>
          </div>
        </div>

        <div class="buatkarya-buttons">
          <button type="button" class="kembali-btn" onclick="goBack()">
            Return
          </button>
          <button type="submit" class="signup-submit-btn">Publish</button>
        </div>
      </form>
    </div>

    <script>
      let uploadedImages = [];
      const allowedImageTypes = ['image/png', 'image/jpeg']; 

      document
        .getElementById("chapter-images")
        .addEventListener("change", function (e) {
          const files = Array.from(e.target.files);
          // When new files are selected, we want to update the preview and *sort them*.
          // The `uploadedImages` array is for the preview.
          uploadedImages = []; // Clear previous selection for new batch if not adding to existing

          // Convert FileList to Array for sorting
          let newFiles = Array.from(e.target.files);

          // Sort the files by name before processing for display and later submission
          newFiles.sort((a, b) => {
              // Extract just the numerical part of the filename for natural sorting if applicable
              // E.g., '1.jpg' vs '10.jpg'
              const numA = parseInt(a.name.match(/\d+/));
              const numB = parseInt(b.name.match(/\d+/));

              if (!isNaN(numA) && !isNaN(numB)) {
                  return numA - numB; // Sort numerically if both are numbers
              }
              // Fallback to alphabetical sort if not numerical or mixed
              return a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' });
          });


          newFiles.forEach((file) => {
            if (allowedImageTypes.includes(file.type)) { 
              const reader = new FileReader();
              reader.onload = function (event) {
                const imageData = {
                  file: file, // Store the actual File object
                  name: file.name,
                  size: formatFileSize(file.size),
                  src: event.target.result,
                  id: Date.now() + Math.random(), // Unique ID for preview removal
                };
                uploadedImages.push(imageData);
                updateImageDisplay(); // Update display after adding each image
              };
              reader.readAsDataURL(file);
            } else {
              alert(
                `File ${file.name} is not a valid image type. Only PNG, JPG, JPEG files are accepted.`
              );
            }
          });
        });

      function updateImageDisplay() {
        const counter = document.getElementById("images-counter");
        const container = document.getElementById("uploaded-images");

        counter.textContent = `${uploadedImages.length} images selected`;

        if (uploadedImages.length > 0) {
          container.style.display = "block";
          // Important: Re-sort `uploadedImages` *before* mapping to HTML,
          // because `uploadedImages` might be modified by `removeImage`.
          // The sort function here should be consistent with how you want them processed.
          uploadedImages.sort((a, b) => {
              const numA = parseInt(a.name.match(/\d+/));
              const numB = parseInt(b.name.match(/\d+/));

              if (!isNaN(numA) && !isNaN(numB)) {
                  return numA - numB;
              }
              return a.name.localeCompare(b.name, undefined, { numeric: true, sensitivity: 'base' });
          });

          container.innerHTML = uploadedImages
            .map(
              (img) => `
                        <div class="image-item">
                            <img src="${img.src}" alt="${img.name}" class="image-preview">
                            <div class="image-info">
                                <div class="image-name">${img.name}</div>
                                <div class="image-size">${img.size}</div>
                            </div>
                            <button type="button" class="remove-image-btn" onclick="removeImage('${img.id}')">Remove</button>
                        </div>
                    `
            )
            .join("");
        } else {
          container.style.display = "none";
        }
      }

      function removeImage(imageId) {
        // Remove from the preview array
        uploadedImages = uploadedImages.filter((img) => img.id != imageId);
        updateImageDisplay();

        // Also, if you want this to affect what's sent, you'd need to:
        // 1. Get the original FileList from the input.
        // 2. Convert it to an array, filter out the removed file.
        // 3. Create a new DataTransfer object.
        // 4. Add the remaining files to the DataTransfer object.
        // 5. Assign the DataTransfer.files to the input's files property.
        // This is more complex than direct HTML manipulation. For now, we rely on
        // the form submit handler to grab files from the input *after* user has finished.
        // If the user selects files, then removes one from the preview, then adds more,
        // the current `FormData` logic might not correctly send only the *intended* files.
        // A simpler approach for the current setup is that `clearAllImages` is the main way to change selection.
        // Individual removal from preview implies the user might re-select all without the unwanted one.
      }

      function clearAllImages() {
        uploadedImages = [];
        document.getElementById("chapter-images").value = ""; 
        updateImageDisplay();
      }

      function formatFileSize(bytes) {
        if (bytes === 0) return "0 Bytes";
        const k = 1024;
        const sizes = ["Bytes", "KB", "MB", "GB"];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + " " + sizes[i];
      }

      function toggleRadio(group, option) {
        document
          .querySelectorAll(`input[name=${group}_status]`)
          .forEach((radio) => {
            radio.checked = false;
          });
        document
          .querySelectorAll(`.radio-input[id^=${group}-]`)
          .forEach((input) => {
            input.classList.remove("checked");
          });

        document
          .getElementById(`${group}-${option}`)
          .classList.add("checked");
        document.querySelector(
          `input[name=${group}_status][value=${option}]`
        ).checked = true;

        const scheduleFields = document.getElementById("schedule-fields");
        if (group === "publish") {
          if (option === "schedule") {
            scheduleFields.style.display = "flex";
            setCurrentDateTimeForSchedule(); 
          } else {
            scheduleFields.style.display = "none";
          }
        }
      }

      function setCurrentDateTimeForSchedule() {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0'); 
        const day = String(now.getDate()).padStart(2, '0');
        const hours = String(now.getHours()).padStart(2, '0');
        const minutes = String(now.getMinutes()).padStart(2, '0');

        document.querySelector('.schedule-input[name="schedule_date"]').value = `${year}-${month}-${day}`;
        document.querySelector('.schedule-input[name="schedule_time"]').value = `${hours}:${minutes}`;
        document.querySelector('.schedule-input[name="schedule_minute"]').value = `${minutes}`; 
      }

      function goBack() {
        window.location.href = '../Creator\'s/creator-karya.php'; 
      }

      document
        .getElementById("chapterUploadForm")
        .addEventListener("submit", function (event) {
          event.preventDefault(); 

          if (uploadedImages.length === 0) {
            alert("Please select at least one image file for the chapter (PNG, JPG, or JPEG).");
            return; 
          }

          const form = event.target;
          const formData = new FormData(); // Start with empty FormData to manually add files

          // Add regular form fields first
          formData.append('work_id', document.getElementById('work_id').value);
          formData.append('chapter_title', form.elements.chapter_title.value);
          formData.append('comments_status', form.elements.comments_status.value);
          formData.append('publish_type', form.elements.publish_type.value);

          // If publish_type is 'schedule', add schedule fields
          if (form.elements.publish_type.value === 'schedule') {
              formData.append('schedule_date', form.elements.schedule_date.value);
              formData.append('schedule_time', form.elements.schedule_time.value);
              formData.append('schedule_minute', form.elements.schedule_minute.value);
          }
          
          // Manually append files in the desired order (from uploadedImages array which is sorted)
          uploadedImages.forEach((imageData) => {
              formData.append('chapter_images[]', imageData.file, imageData.name); // Pass original File object and its name
          });

          // Optional: Add loading indicator here

          const uploadUrl = '../php/upload_chapter.php'; 

          fetch(uploadUrl, {
            method: 'POST',
            body: formData 
          })
          .then(response => {
            if (!response.ok) {
              return response.text().then(text => { 
                console.error('Server response (non-OK):', text);
                throw new Error('HTTP error! Status: ' + response.status + ' - ' + text);
              });
            }
            return response.json(); 
          })
          .then(data => {
            // Optional: Remove loading indicator here
            if (data.success) {
              alert(data.message); 
              window.location.href = '../Creator\'s/creator-karya.php'; 
            } else {
              alert('Error: ' + data.message); 
            }
          })
          .catch(error => {
            // Optional: Remove loading indicator here
            console.error('Fetch error:', error);
            alert('An unexpected error occurred: ' + error.message); 
          });
        });

      // Initialize schedule fields visibility and set default date/time if schedule is active
      document.addEventListener("DOMContentLoaded", function () {
        const scheduleFields = document.getElementById("schedule-fields");
        if (document.getElementById("publish-schedule").classList.contains("checked")) {
          scheduleFields.style.display = "flex";
          setCurrentDateTimeForSchedule(); 
        } else {
          scheduleFields.style.display = "none";
        }

        document.querySelector(
          'input[name="comments_status"][value="on"]'
        ).checked = true;
        document.querySelector(
          'input[name="publish_type"][value="schedule"]' 
        ).checked = true;
      });
    </script>
  </body>
</html>