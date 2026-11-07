<?php

namespace App\Services;

use App\Models\Job;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GeminiService
{
    protected string $apiKey;
    protected string $model;
    protected string $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key') ?: env('GEMINI_API_KEY', '');
        $this->model = config('services.gemini.model') ?: env('GEMINI_MODEL', 'gemini-3.5-flash');
        $this->baseUrl = "https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent";
    }

    /**
     * Kiểm tra xem API Key đã được cấu hình chưa
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Gửi yêu cầu sinh nội dung văn bản tới Google Gemini
     */
    public function generateText(string $prompt, array $history = [], ?string $systemInstruction = null): ?string
    {
        if (!$this->isConfigured()) {
            return null;
        }

        try {
            $contents = [];

            // Thêm các lượt trò chuyện trước (nếu có)
            foreach (array_slice($history, -6) as $turn) {
                if (isset($turn['role']) && isset($turn['text'])) {
                    $role = ($turn['role'] === 'user') ? 'user' : 'model';
                    $contents[] = [
                        'role' => $role,
                        'parts' => [['text' => $turn['text']]]
                    ];
                }
            }

            // Thêm tin nhắn hiện tại
            $contents[] = [
                'role' => 'user',
                'parts' => [['text' => $prompt]]
            ];

            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.85,
                    'maxOutputTokens' => 800,
                ]
            ];

            if (!empty($systemInstruction)) {
                $payload['system_instruction'] = [
                    'parts' => [['text' => $systemInstruction]]
                ];
            }

            $url = "{$this->baseUrl}?key={$this->apiKey}";

            $response = Http::timeout(25)->post($url, $payload);

            if ($response->successful()) {
                $data = $response->json();
                $parts = $data['candidates'][0]['content']['parts'] ?? [];
                $textParts = [];
                foreach ($parts as $part) {
                    if (isset($part['text'])) {
                        $textParts[] = $part['text'];
                    }
                }
                if (!empty($textParts)) {
                    return implode("\n", $textParts);
                }
            } else {
                Log::warning('Gemini API Error: ' . $response->body());
            }
        } catch (\Exception $e) {
            Log::error('Gemini Service Exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Trợ lý Chat Nghề Nghiệp TopWork (gắn sẵn dữ liệu việc làm thực tế)
     */
    public function chatCareer(string $message, array $history = []): ?string
    {
        // Lấy 8 việc làm đang mở trên hệ thống để AI gợi ý đúng việc có thật
        $recentJobs = Job::where('status', 1)->take(8)->get(['id', 'title', 'company_name', 'salary', 'province']);
        $jobsContext = "";
        foreach ($recentJobs as $j) {
            $jobsContext .= "- {$j->title} tại {$j->company_name} (Lương: {$j->salary}, Khu vực: {$j->province}). Link chi tiết: /jobs/detail/{$j->id}\n";
        }

        $systemPrompt = "Bạn là trợ lý AI của TopWork — nền tảng tuyển dụng Việt Nam. "
            . "Trả lời ngắn gọn, tự nhiên bằng tiếng Việt. Dùng markdown (bold, list). "
            . "Hỗ trợ: tìm việc làm, tư vấn CV (công thức XYZ/STAR, tránh sáo rỗng), luyện phỏng vấn, đàm phán lương. "
            . "Khi gợi ý việc làm, dùng link dạng [Xem chi tiết](/jobs/detail/{id}).\n\n"
            . "Việc làm đang mở trên hệ thống:\n" . $jobsContext;

        return $this->generateText($message, $history, $systemPrompt);
    }

    /**
     * Tự động sinh mô tả công việc (Job Description) cho Nhà tuyển dụng
     */
    public function generateJobDescription(string $title, string $level = 'Nhân viên'): ?string
    {
        $prompt = "Hãy viết bản mô tả công việc chi tiết và chuyên nghiệp cho vị trí: {$title} (Cấp bậc: {$level}). "
            . "Format gồm 3 mục rõ ràng: 1. Trách nhiệm công việc, 2. Yêu cầu chuyên môn & kinh nghiệm, 3. Quyền lợi được hưởng.";

        $systemPrompt = "Bạn là chuyên gia nhân sự tuyển dụng cao cấp. Hãy viết Job Description hấp dẫn, súc tích và chuẩn mực.";

        return $this->generateText($prompt, [], $systemPrompt);
    }
}

