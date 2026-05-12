const profileImageInput = document.getElementById("profileImageInput");
const profilePreview = document.getElementById("profilePreview");
const profileImageForm = document.getElementById("profileImageForm");

if (profileImageInput && profilePreview && profileImageForm) {
  profileImageInput.addEventListener("change", function () {
    if (this.files.length > 0) {
      profilePreview.src = URL.createObjectURL(this.files[0]);
      profileImageForm.submit();
    }
  });
}

const imageInput = document.getElementById("profile_image");
const previewImage = document.getElementById("uploadPreview");
const uploadText = document.getElementById("uploadText");

if (imageInput && previewImage) {
  imageInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      previewImage.src = URL.createObjectURL(file);

      if (uploadText) {
        uploadText.innerHTML = "Valgt billede:<br>" + file.name;
      }
    }
  });
}