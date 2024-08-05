// toggle filter table
$(document).ready(function () {
	// Search filter
	$("#searchInput").on("keyup", function () {
		var value = $(this).val().toLowerCase();
		$("#userTableBody tr").filter(function () {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
		});
	});

	// Sort table columns
	$("th").click(function () {
		var table = $(this).parents("table").eq(0);
		var rows = table
			.find("tr:gt(0)")
			.toArray()
			.sort(comparer($(this).index()));
		this.asc = !this.asc;
		if (!this.asc) {
			rows = rows.reverse();
		}
		for (var i = 0; i < rows.length; i++) {
			table.append(rows[i]);
		}
	});

	function comparer(index) {
		return function (a, b) {
			var valA = getCellValue(a, index),
				valB = getCellValue(b, index);
			return $.isNumeric(valA) && $.isNumeric(valB)
				? valA - valB
				: valA.localeCompare(valB);
		};
	}

	function getCellValue(row, index) {
		return $(row).children("td").eq(index).text();
	}
});

// togle lihat password
document
	.getElementById("togglePassword")
	.addEventListener("click", function () {
		var passwordInput = document.getElementById("pwd");
		var toggleIcon = document.getElementById("toggleIcon");
		if (passwordInput.type === "password") {
			passwordInput.type = "text";
			toggleIcon.classList.remove("bi-eye-fill");
			toggleIcon.classList.add("bi-eye-slash-fill");
		} else {
			passwordInput.type = "password";
			toggleIcon.classList.remove("bi-eye-slash-fill");
			toggleIcon.classList.add("bi-eye-fill");
		}
	});

// Toggle password visibility for Edit modal
document.getElementById("togglePasswordEdit").addEventListener("click", function () {
    var passwordInput = document.getElementById("password_m");
    var toggleIcon = document.getElementById("toggleIconEdit");
    if (passwordInput.type === "password") {
        passwordInput.type = "text";
        toggleIcon.classList.remove("bi-eye-fill");
        toggleIcon.classList.add("bi-eye-slash-fill");
    } else {
        passwordInput.type = "password";
        toggleIcon.classList.remove("bi-eye-slash-fill");
        toggleIcon.classList.add("bi-eye-fill");
    }
});