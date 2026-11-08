# Thư Viện Snippet & CSS Pattern Chuẩn UI Hiện Đại

Các mẫu CSS và HTML chuẩn tái sử dụng cho dự án:

### 1. Bento Grid / Job Card Hiện Đại
```html
<div class="tw-card p-4 rounded-3 border bg-white shadow-sm transition-all hover-lift">
  <div class="d-flex align-items-center gap-3 mb-3">
    <img src="..." class="rounded-2 border" style="width: 52px; height: 52px; object-fit: cover;">
    <div class="flex-grow-1 min-w-0">
      <h6 class="fw-bold mb-1 text-truncate text-dark">Tiêu đề việc làm / thẻ</h6>
      <p class="text-muted small mb-0 text-truncate">Tên công ty hoặc mô tả phụ</p>
    </div>
  </div>
  <div class="d-flex flex-wrap gap-2 mb-3">
    <span class="badge bg-light text-dark border px-2 py-1 font-weight-normal">Hà Nội</span>
    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">15 - 25 triệu</span>
  </div>
</div>
```

### 2. Badge Trạng Thái Chuẩn (Status Badges)
```html
<!-- Thành công -->
<span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
  <i class="fa fa-check-circle me-1"></i> Đã duyệt
</span>

<!-- Chờ xử lý -->
<span class="badge bg-warning-subtle text-warning border border-warning-subtle rounded-pill px-3 py-1">
  <i class="fa fa-clock-o me-1"></i> Chờ duyệt
</span>

<!-- Đã ẩn / Ngưng tuyển -->
<span class="badge bg-secondary-subtle text-secondary border rounded-pill px-3 py-1">
  <i class="fa fa-pause-circle me-1"></i> Tạm ngưng
</span>
```

### 3. Focus Ring & Input Form Mịn
```css
.modern-input {
  border: 1px solid #d1d5db;
  border-radius: 8px;
  padding: 10px 14px;
  font-size: 14px;
  color: #111827;
  background-color: #ffffff;
  transition: all 0.15s ease-in-out;
}

.modern-input:focus {
  outline: none;
  border-color: #00b14f;
  box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.16);
}
```

### 4. Bảng Dữ Liệu Tinh Gọn (Data Tables)
```html
<div class="table-responsive rounded-3 border bg-white shadow-sm">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light text-muted small text-uppercase">
      <tr>
        <th class="ps-3 py-3">Tên / Tiêu đề</th>
        <th>Trạng thái</th>
        <th>Ngày tạo</th>
        <th class="text-end pe-3">Thao tác</th>
      </tr>
    </thead>
    <tbody>
      ...
    </tbody>
  </table>
</div>
```

