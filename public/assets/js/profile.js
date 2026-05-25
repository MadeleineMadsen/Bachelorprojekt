// ------------------------------------------------
// Profilbillede på profilside
// ------------------------------------------------

// Henter elementer
const profileImageInput = document.getElementById("profileImageInput");
const profilePreview = document.getElementById("profilePreview");
const profileImageForm = document.getElementById("profileImageForm");

// Opdaterer preview og sender formular automatisk ved nyt profilbillede
if (profileImageInput && profilePreview && profileImageForm) {
  profileImageInput.addEventListener("change", function () {
    if (this.files.length > 0) {
      profilePreview.src = URL.createObjectURL(this.files[0]);
      profileImageForm.submit();
    }
  });
}

// ------------------------------------------------
// Upload preview på formularer
// ------------------------------------------------

// Henter elementer
const imageInput = document.getElementById("profile_image");
const previewImage = document.getElementById("uploadPreview");
const uploadText = document.getElementById("uploadText");

// Viser preview af valgt billede
if (imageInput && previewImage) {
  imageInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      previewImage.src = URL.createObjectURL(file);

      // Opdaterer upload tekst med filnavn
      if (uploadText) {
        uploadText.innerHTML = "Valgt billede:<br>" + file.name;
      }
    }
  });
}
