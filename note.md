{{ Str::words(strip_tags($featureJob->description), 5, "...") }}
{!! nl2br($job->description) !!}
{{ \Carbon\Carbon::parse($item->created_at)->format("d/m/Y") }}
{!! file_get_contents(public_path("admin/icon/circle-arrow-left.svg")) !!}
findOrFail
firstOrFail
$product = Product::findOrFail($id);

<div class="row g-2">
              <div class="mb-3 col-md-6">
                <label for="title" class="form-label">Tỉnh/TP</label>
                <input type="text" class="form-control" id="title" name="title">
              </div>
              <div class="mb-3 col-md-6">
                <label for="title" class="form-label">Quận/huyện</label>
                <input type="text" class="form-control" id="title" name="title">
              </div>
            </div>
            <div class="row g-2">
              <div class="mb-3 col-md-6">
                <label for="title" class="form-label">Xã/phường</label>
                <input type="text" class="form-control" id="title" name="title">
              </div>
              <div class="mb-3 col-md-6">
                <label for="title" class="form-label">Địa chỉ cụ thể</label>
                <input type="text" class="form-control" id="title" name="title">
              </div>
</div>
UPDATE users SET password = '$2y$12$IkTRACq.d0WHufbUj7oSnu22lJt0OE13SdM1Btfsyn/TG8g4cQ0ny';
Abc@12345
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
