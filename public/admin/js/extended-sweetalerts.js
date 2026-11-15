const deleteButton = document.querySelectorAll(".delete");
deleteButton.forEach((item) =>
    item.addEventListener("click", function (t) {
        t.preventDefault();
        const form = this.closest("form") || document.getElementById("deleteForm");
        Swal.fire({
            title: "Bạn có muốn xóa không?",
            text: "Bạn sẽ không thể khôi phục lại!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            cancelButtonText: "Hủy bỏ",
            confirmButtonText: "Ok",
        }).then((result) => {
            if (result.isConfirmed && form) {
                form.submit();
            }
        });
    })
);
