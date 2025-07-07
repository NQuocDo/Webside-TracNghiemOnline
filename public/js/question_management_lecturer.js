document.addEventListener("DOMContentLoaded", function () {
    alertify.set("notifier", "position", "top-right");
    alertify.set("notifier", "delay", 3);
    const scopeDropDown = document.querySelectorAll(".scope-dropdown");
    //thay đổi phạm vi
    scopeDropDown.forEach((dropdown) => {
        dropdown.addEventListener("change", function () {
            const questionId = this.dataset.id;
            const phamViMoi = this.value;
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch(`/lecturer/question/${questionId}/update-scope`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
                body: JSON.stringify({ pham_vi: phamViMoi }),
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((errorData) => {
                            throw new Error(
                                errorData.message ||
                                    "Có lỗi xảy ra khi cập nhật phạm vi."
                            );
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    // Xử lý dữ liệu trả về từ server
                    if (data.success) {
                        console.log(data.message);
                        alertify.success(data.message);
                    } else {
                        console.error("Lỗi từ server:", data.message);
                        alertify.error("Lỗi: " + data.message);
                    }
                })
                .catch((error) => {
                    console.error("Lỗi Fetch API:", error);
                    alertify.error(
                        "Đã xảy ra lỗi không mong muốn: " + error.message
                    );
                });
        });
    });
    //xoá tạm thời
    const deleteButtons = document.querySelectorAll(".delete-btn"); // Chọn tất cả các nút có class .delete-btn

    deleteButtons.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Ngăn chặn hành vi mặc định của nút

            const questionId = this.dataset.id;
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const trangThaiMoi = "an";

            //popup xác nhận
            const thongBaoTieuDeXacNhan = "Xác nhận xoá câu hỏi"; // Tiêu đề xác nhận rõ ràng
            const thongBaoNoiDungXacNhan =
                "Bạn có chắc chắn muốn xoá câu hỏi này không?";

            // Hiển thị popup xác nhận bằng AlertifyJS
            alertify.confirm(
                thongBaoTieuDeXacNhan,
                thongBaoNoiDungXacNhan,
                function () {
                    fetch(`/lecturer/question/${questionId}/update-status`, {
                        method: "PUT",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": csrfToken,
                        },

                        body: JSON.stringify({ trang_thai_moi: trangThaiMoi }),
                    })
                        .then((response) => {
                            if (!response.ok) {
                                return response.text().then((text) => {
                                    console.error(
                                        "Server returned non-OK status or non-JSON:",
                                        text
                                    );
                                    throw new Error(
                                        "Lỗi server hoặc phản hồi không hợp lệ. Vui lòng kiểm tra console."
                                    );
                                });
                            }
                            return response.json();
                        })
                        .then((data) => {
                            if (data.success) {
                                alertify.success(data.message);
                                if (data.new_status === "an") {
                                    const rowToRemove = button.closest("tr");
                                    if (rowToRemove) {
                                        rowToRemove.remove();
                                    }
                                }
                            } else {
                                alertify.error("Lỗi: " + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error(
                                "Lỗi Fetch API khi cập nhật trạng thái:",
                                error
                            );
                            alertify.error(
                                "Đã xảy ra lỗi không mong muốn khi cập nhật trạng thái: " +
                                    error.message
                            );
                        });
                },
                function () {
                    alertify.error("Bạn đã hủy thao tác.");
                }
            );
        });
    });
    const restoreButton = document.querySelectorAll(".btn-restore"); // Chọn tất cả các nút có class .delete-btn

    restoreButton.forEach((button) => {
        button.addEventListener("click", function (event) {
            event.preventDefault(); // Ngăn chặn hành vi mặc định của nút

            const questionId = this.dataset.id;
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");
            const trangThaiMoi = "hien";

            //popup xác nhận
            const thongBaoTieuDeXacNhan = "Xác nhận khôi phục câu hỏi"; // Tiêu đề xác nhận rõ ràng
            const thongBaoNoiDungXacNhan =
                "Bạn có chắc chắn muốn khôi phục câu hỏi này không?";

            // Hiển thị popup xác nhận bằng AlertifyJS
            alertify.confirm(
                thongBaoTieuDeXacNhan,
                thongBaoNoiDungXacNhan,
                function () {
                    fetch(
                        `/lecturer/question_del/${questionId}/update-status`,
                        {
                            method: "PUT",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": csrfToken,
                            },

                            body: JSON.stringify({
                                trang_thai_moi: trangThaiMoi,
                            }),
                        }
                    )
                        .then((response) => {
                            if (!response.ok) {
                                return response.text().then((text) => {
                                    console.error(
                                        "Server returned non-OK status or non-JSON:",
                                        text
                                    );
                                    throw new Error(
                                        "Lỗi server hoặc phản hồi không hợp lệ. Vui lòng kiểm tra console."
                                    );
                                });
                            }
                            return response.json();
                        })
                        .then((data) => {
                            if (data.success) {
                                alertify.success(data.message);
                                if (data.new_status === "an") {
                                    const rowToRemove = button.closest("tr");
                                    if (rowToRemove) {
                                        rowToRemove.remove();
                                    }
                                }
                            } else {
                                alertify.error("Lỗi: " + data.message);
                            }
                        })
                        .catch((error) => {
                            console.error(
                                "Lỗi Fetch API khi cập nhật trạng thái:",
                                error
                            );
                            alertify.error(
                                "Đã xảy ra lỗi không mong muốn khi cập nhật trạng thái: " +
                                    error.message
                            );
                        });
                },
                function () {
                    alertify.error("Bạn đã hủy thao tác.");
                }
            );
        });
    });
    //preview ảnh
    const thumbnails = document.querySelectorAll(".thumb-img");

    thumbnails.forEach((img) => {
        img.addEventListener("click", function () {
            const id = this.dataset.id;
            const previewImg = document.getElementById(`preview-${id}`);

            // Ẩn tất cả ảnh preview khác
            document.querySelectorAll(".preview-img").forEach((p) => {
                if (p.id !== `preview-${id}`) p.style.display = "none";
            });

            // Toggle ảnh hiện tại
            previewImg.style.display =
                previewImg.style.display === "none" ? "block" : "none";
        });
    });

    //tìm kiếm
    const form = document.getElementById("filter-form");
    const keywordInput = document.getElementById("keyword");
    const monHocSelect = document.getElementById("monHocSelect");

    // Khi chọn môn học, submit ngay
    if (monHocSelect) {
        monHocSelect.addEventListener("change", function () {
            form.submit();
        });
    }

    // Khi nhập từ khóa: debounce 500ms rồi submit
    if (keywordInput) {
        let debounce;
        keywordInput.addEventListener("input", function () {
            clearTimeout(debounce);
            debounce = setTimeout(() => {
                form.submit();
            }, 500);
        });
    }
    const checkAll = document.getElementById("checkAll");
    const checkboxes = document.querySelectorAll(".question-checkbox");

    // Lấy dữ liệu đã chọn trước đó từ localStorage
    let selectedQuestions = JSON.parse(
        localStorage.getItem("selectedQuestions") || "[]"
    );

    // Đánh dấu lại các checkbox đã chọn từ localStorage
    checkboxes.forEach((cb) => {
        if (selectedQuestions.includes(cb.value)) {
            cb.checked = true;
        }

        // Mỗi khi checkbox thay đổi, cập nhật lại localStorage
        cb.addEventListener("change", function () {
            const val = this.value;
            if (this.checked) {
                if (!selectedQuestions.includes(val)) {
                    selectedQuestions.push(val);
                }
            } else {
                selectedQuestions = selectedQuestions.filter(
                    (id) => id !== val
                );
            }
            localStorage.setItem(
                "selectedQuestions",
                JSON.stringify(selectedQuestions)
            );
        });
    });

    // Xử lý "Chọn tất cả" trên trang hiện tại
    if (checkAll) {
        checkAll.addEventListener("change", function () {
            checkboxes.forEach((cb) => {
                cb.checked = this.checked;

                const val = cb.value;
                if (this.checked) {
                    if (!selectedQuestions.includes(val)) {
                        selectedQuestions.push(val);
                    }
                } else {
                    selectedQuestions = selectedQuestions.filter(
                        (id) => id !== val
                    );
                }
            });
            localStorage.setItem(
                "selectedQuestions",
                JSON.stringify(selectedQuestions)
            );
        });
    } 

    const formCreateQuetion = document.getElementById("form-create-exam");
    const createExamBtn = document.getElementById("create-exam-btn");

    if (formCreateQuetion && createExamBtn) {
        createExamBtn.addEventListener("click", function () {

            const oldHidden = document.querySelectorAll(
                ".dynamic-hidden-question"
            );
            oldHidden.forEach((el) => el.remove());

            selectedQuestions.forEach((id) => {
                const input = document.createElement("input");
                input.type = "hidden";
                input.name = "ma_cau_hoi[]";
                input.value = id;
                input.classList.add("dynamic-hidden-question");
                formCreateQuetion.appendChild(input);
            });

            formCreateQuetion.submit();
        });
    }
});
