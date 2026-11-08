---
name: modern-ui-designer
description: Expert UI/UX design and frontend styling skill. Use when creating, upgrading, or polishing UI components, layouts, pages, animations, responsive design, color schemes, and design systems for web applications.
license: MIT
---

# Modern UI/UX Designer Skill

Bộ quy chuẩn và hướng dẫn thiết kế giao diện hiện đại (Modern Web UI/UX), lấy cảm hứng từ các Design System hàng đầu thế giới: **GitHub Primer, Atlassian Design System, Tailwind UI, Apple Human Interface Guidelines và Stripe UI**.

Sử dụng skill này bất cứ khi nào bạn cần tạo mới, nâng cấp, làm đẹp trang web, tối ưu trải nghiệm người dùng hoặc thiết kế component.

---

## 1. Nguyên Tắc Cốt Lõi (Core Principles)

### 1.1. Hệ Lưới & Khoảng Cách 4px / 8px (Grid & Spacing)
- Tất cả padding, margin, gap phải chia hết cho 4 (hoặc 8): `4px, 8px, 12px, 16px, 20px, 24px, 32px, 48px, 64px`.
- Tránh các khoảng cách ngẫu nhiên như `13px`, `19px`, `27px`.
- Phân tầng khoảng cách rõ ràng:
  - Giữa các item con trong 1 card: `8px - 12px`.
  - Padding trong card: `16px - 24px`.
  - Giữa các card / column: `16px - 24px`.
  - Giữa các Section lớn trên trang: `48px - 80px`.

### 1.2. Thang Màu Sắc & Độ Tương Phản (Color Palette & Depth)
- **Neutral Palette (Màu trung tính):** Dùng màu xám dịu mắt (như Slate hoặc Zinc), tránh màu đen tuyệt đối `#000000` cho chữ.
  - Chữ chính (Heading/Body): `#0f172a` hoặc `#111827`.
  - Chữ phụ (Subtext/Meta): `#64748b` hoặc `#6b7280`.
  - Border viền: `1px solid #e2e8f0` hoặc `rgba(0, 0, 0, 0.08)`.
  - Nền phụ (Background sub): `#f8fafc` hoặc `#f9fafb`.
- **Brand & Accent (Màu thương hiệu):** Chỉ dùng màu chính (Primary) cho các điểm nhấn quan trọng nhất (CTA chính, Active state, Badges nổi bật).
- **Phân lớp chiều sâu (Elevation & Shadows):** Dùng đổ bóng mềm nhiều lớp thay vì bóng đậm cục bộ:
  ```css
  /* Soft Layered Shadow */
  box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.05), 0 1px 2px -1px rgba(0, 0, 0, 0.05);
  /* Card Hover Elevation */
  box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.08), 0 8px 10px -6px rgba(0, 0, 0, 0.04);
  ```

### 1.3. Phân Cấp Kiểu Chữ (Typography Hierarchy)
- **Font Stack hiện đại:** Ưu tiên hệ font hệ thống sạch sẽ:
  `-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif`.
- **Kích thước & Trọng số (Font Sizes & Weights):**
  - Section Heading: `28px - 36px`, `font-weight: 700 - 800`, `letter-spacing: -0.02em`.
  - Card Title: `16px - 18px`, `font-weight: 600`.
  - Body text: `14px - 15px`, `font-weight: 400`, `line-height: 1.5 - 1.6`.
  - Small / Meta text: `12px - 13px`, `font-weight: 500`.
- Luôn giữ tỷ lệ tương phản chữ đạt chuẩn **WCAG AA** (tối thiểu 4.5:1).

---

## 2. Thiết Kế Component Hiện Đại (Component Patterns)

### 2.1. Thẻ & Khối Hiển Thị (Cards & Bento Grid)
- Bo góc hiện đại (Border Radius): `12px - 16px` cho Card, `999px` cho Pill/Badge.
- Đường viền tinh tế: Luôn kết hợp border siêu mỏng `1px solid rgba(0,0,0,0.06)` cùng nền trắng `background: #ffffff` để tách biệt rõ với nền trang.
- Hiệu ứng Hover mượt:
  ```css
  .modern-card {
    transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease, border-color 0.2s ease;
  }
  .modern-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 24px -8px rgba(0, 0, 0, 0.08);
    border-color: rgba(0, 177, 79, 0.3);
  }
  ```

### 2.2. Nút Bấm & Trạng Thái Tương Tác (Buttons & Micro-interactions)
- Chiều cao nút chuẩn: `36px - 40px` (Desktop), tối thiểu `44px` (Touch target trên Mobile).
- Luôn có đủ các trạng thái: `default`, `hover`, `active` (bấm lún xuống `transform: scale(0.98)`), `disabled` và `loading`.
- Nút bấm chính (Primary) dùng gradient nhẹ hoặc màu đồng nhất với hiệu ứng đổ bóng màu:
  ```css
  .btn-modern-primary {
    background: #00b14f;
    color: #ffffff;
    font-weight: 500;
    border-radius: 8px;
    padding: 8px 18px;
    box-shadow: 0 1px 2px rgba(0,0,0,0.06), 0 1px 3px rgba(0, 177, 79, 0.2);
    transition: all 0.15s ease;
  }
  .btn-modern-primary:hover {
    background: #009944;
    box-shadow: 0 4px 12px rgba(0, 177, 79, 0.3);
  }
  ```

### 2.3. Form Nhập Liệu & Bộ Lọc (Inputs & Selects)
- Input có viền rõ ràng `border: 1px solid #d1d5db`, padding `10px 14px`, bo góc `8px`.
- Trạng thái `focus` nổi bật với vòng hào quang (focus ring):
  `outline: none; border-color: #00b14f; box-shadow: 0 0 0 3px rgba(0, 177, 79, 0.15);`.
- Label đặt phía trên, ngắn gọn, dễ đọc, kèm placeholder có ý nghĩa ví dụ cụ thể.

### 2.4. Huy Hiệu & Nhãn (Badges & Status Chips)
- Dùng màu pastel dịu (light background + dark saturated text) thay vì màu chói:
  - *Thành công (Active / Đã duyệt):* Nền `#ecfdf5`, chữ `#047857`, viền `#a7f3d0`.
  - *Chờ xử lý:* Nền `#fffbeb`, chữ `#b45309`, viền `#fde68a`.
  - *Nguy hiểm / Hết hạn:* Nền `#fef2f2`, chữ `#b91c1c`, viền `#fecaca`.
  - *Thông tin / Trung tính:* Nền `#f1f5f9`, chữ `#334155`, viền `#e2e8f0`.

---

## 3. Responsive & Trải Nghiệm Di Động (Mobile Polish)

1. **Fluid Typography & Containers:** Dùng `clamp()` hoặc Bootstrap container chuẩn, tránh overflow ngang màn hình (`overflow-x: hidden`).
2. **Ngăn vỡ layout:** Các bảng dữ liệu (Table) luôn bọc trong `.table-responsive` kèm cuộn ngang mượt.
3. **Touch Targets:** Mọi nút bấm, icon action, dropdown toggle phải có vùng chạm tối thiểu `44x44px` trên điện thoại.
4. **Tránh kẹt chữ:** Sử dụng `text-truncate` hoặc `line-clamp-2` cho tiêu đề và mô tả dài để giao diện luôn cân đối, ngăn nắp.

---

## 4. Danh Mục Kiểm Tra Tự Động (Self-Review Checklist)

Trước khi hoàn thành bất kỳ thay đổi giao diện nào, tự kiểm tra:
- [ ] Giao diện có đồng bộ với Design System hiện tại của dự án không?
- [ ] Khoảng cách lề (padding/margin) có theo hệ 4px/8px không?
- [ ] Đã có đủ hover effect, focus ring và active state cho các phần tử tương tác chưa?
- [ ] Các ảnh hiển thị có bị méo tỷ lệ không (dùng `object-fit: cover`)?
- [ ] Giao diện trên màn hình nhỏ (Mobile < 768px) có bị tràn lề hay đè chữ không?
- [ ] Đã dọn dẹp các đoạn CSS inline dư thừa, gom vào file hoặc thẻ style chung chưa?

