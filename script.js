function filterRecipes() {
  const selected = document.getElementById("categoryFilter").value;
  const rows = document.querySelectorAll("#recipesTable tbody tr");

  rows.forEach(row => {
  const category = row.cells[4].innerText.trim().toLowerCase();
row.style.display =
  selected === "all" || category === selected.toLowerCase() ? "" : "none";


  });
}
window.addEventListener("load", function () {
  const savedImage = localStorage.getItem("profileImage");
  const profileImg = document.getElementById("profileImage");

  if (savedImage) {
    profileImg.src = savedImage;   // صورة المستخدم
  } else {
    profileImg.src = "default-profile.png"; // صورة افتراضية
  }
});

const imageInput = document.getElementById("imageInput");

if (imageInput) {
  imageInput.addEventListener("change", function () {
    const file = this.files[0];

    if (file) {
      const reader = new FileReader();

      reader.onload = function () {
        localStorage.setItem("profileImage", reader.result);
      };

      reader.readAsDataURL(file);
    }
  });
}

