document
  .getElementById("profileImageInput")
  .addEventListener("change", function () {
    if (this.files.length > 0) {
      document.getElementById("profilePreview").src = URL.createObjectURL(
        this.files[0],
      );

      document.getElementById("profileImageForm").submit();
    }
  });
