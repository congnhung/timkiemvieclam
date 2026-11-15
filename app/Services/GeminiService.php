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

    /**
     * Tự động sinh trọn gói bản mô tả công việc (JD) có cấu trúc chuẩn mực cho Nhà tuyển dụng
     * Trả về mảng 5 trường: description, responsibility, qualifications, benefits, keywords
     */
    public function generateStructuredJobDescription(string $title, ?string $skills = '', ?string $level = 'Nhân viên', ?string $careerName = ''): array
    {
        $skillsText = !empty($skills) ? $skills : 'chuyên môn phù hợp';
        $levelText = !empty($level) ? $level : 'Nhân viên';
        $careerText = !empty($careerName) ? $careerName : 'Chung';

        if ($this->isConfigured()) {
            $prompt = "Bạn là Giám đốc Nhân sự (HR Director) chuyên nghiệp tại Việt Nam.\n"
                . "Hãy soạn thảo một bản mô tả công việc (Job Description) hoàn chỉnh, hấp dẫn và chuẩn ATS cho vị trí sau:\n"
                . "- Vị trí công việc: {$title}\n"
                . "- Cấp bậc: {$levelText}\n"
                . "- Ngành nghề: {$careerText}\n"
                . "- Kỹ năng mong muốn: {$skillsText}\n\n"
                . "Yêu cầu: Hãy trả về DUY NHẤT một chuỗi JSON hợp lệ (không kèm lời chào hay giải thích nào khác) với đúng các khóa sau:\n"
                . "{\n"
                . "  \"salary\": \"khoảng lương phù hợp theo thị trường Việt Nam (ví dụ: 15 - 20 triệu VNĐ hoặc Thỏa thuận)\",\n"
                . "  \"description\": \"<p>Đoạn văn ngắn 2-3 câu giới thiệu tổng quan về vị trí, mục tiêu và đóng góp của nhân sự trong doanh nghiệp.</p>\",\n"
                . "  \"responsibility\": \"<ul><li>Mô tả nhiệm vụ cụ thể 1</li><li>Mô tả nhiệm vụ cụ thể 2</li><li>Mô tả nhiệm vụ cụ thể 3</li><li>Mô tả nhiệm vụ cụ thể 4</li><li>Mô tả nhiệm vụ cụ thể 5</li></ul>\",\n"
                . "  \"qualifications\": \"<ul><li>Yêu cầu kinh nghiệm, bằng cấp phù hợp</li><li>Kỹ năng chuyên môn cụ thể</li><li>Kỹ năng mềm, tinh thần làm việc nhóm và trách nhiệm</li><li>Khả năng tự học và thích ứng nhanh</li></ul>\",\n"
                . "  \"benefits\": \"<ul><li>Thu nhập cạnh tranh tương xứng năng lực + thưởng hiệu quả công việc / dự án</li><li>Tháng lương thứ 13, thưởng các dịp lễ Tết, sinh nhật</li><li>Đầy đủ chế độ BHXH, BHYT, BHTN theo luật lao động</li><li>Môi trường làm việc cởi mở, cơ hội thăng tiến rõ ràng</li><li>Chế độ đào tạo nội bộ, du lịch hàng năm, teambuilding</li></ul>\",\n"
                . "  \"keywords\": \"danh sách 5-7 từ khóa kỹ năng quan trọng nhất, cách nhau bằng dấu phẩy\"\n"
                . "}";

            $rawText = $this->generateText($prompt, [], "Bạn là hệ thống AI tạo JD chuẩn ATS, chỉ xuất mã JSON hợp lệ.");

            if (!empty($rawText)) {
                $cleaned = preg_replace('/^```(?:json)?\s*|\s*```$/m', '', trim($rawText));
                $parsed = json_decode($cleaned, true);

                if (is_array($parsed) && isset($parsed['description'], $parsed['responsibility'], $parsed['qualifications'], $parsed['benefits'])) {
                    return [
                        'salary' => $parsed['salary'] ?? 'Thỏa thuận',
                        'description' => $parsed['description'],
                        'responsibility' => $parsed['responsibility'],
                        'qualifications' => $parsed['qualifications'],
                        'benefits' => $parsed['benefits'],
                        'keywords' => $parsed['keywords'] ?? '',
                        'source' => 'gemini'
                    ];
                }
            }
        }

        // Dự phòng thông minh nếu API không cấu hình hoặc gặp sự cố
        return $this->generateFallbackJobDescription($title, $skills, $levelText, $careerText);
    }

    /**
     * Bộ sinh dữ liệu JD dự phòng theo ngữ cảnh thông minh (Smart Heuristic Fallback Engine)
     */
    protected function generateFallbackJobDescription(string $title, ?string $skills, string $level, string $career): array
    {
        $lowerTitle = mb_strtolower($title);
        $skillHint = !empty($skills) ? " ({$skills})" : "";

        // 1. Nhóm ngành Công nghệ thông tin / Lập trình (IT & Software)
        if (preg_match('/(developer|lập trình|it|software|backend|frontend|fullstack|php|laravel|react|node|vue|python|java|tester|qa|qc|devops)/i', $title)) {
            return [
                'salary' => '15 - 25 triệu VNĐ (Thỏa thuận)',
                'description' => "<p>Chúng tôi đang tìm kiếm nhân sự tài năng cho vị trí <strong>{$title}</strong> (Cấp bậc: {$level}) gia nhập đội ngũ công nghệ. Bạn sẽ đóng vai trò cốt lõi trong việc thiết kế, xây dựng và tối ưu các sản phẩm phần mềm hiện đại, phục vụ trực tiếp hàng ngàn người dùng.</p>",
                'responsibility' => "<ul>"
                    . "<li>Tham gia phân tích yêu cầu nghiệp vụ, thiết kế kiến trúc và phát triển các tính năng phần mềm cho vị trí {$title}{$skillHint}.</li>"
                    . "<li>Xây dựng và tối ưu hóa hệ thống RESTful API, đảm bảo khả năng mở rộng, độ bảo mật và tốc độ phản hồi cao.</li>"
                    . "<li>Phối hợp chặt chẽ với Product Owner, Designer và bộ phận Kiểm thử (QC) để đảm bảo chất lượng và tiến độ bàn giao sản phẩm.</li>"
                    . "<li>Thực hiện viết Unit Test, Code Review định kỳ và tối ưu hóa hiệu năng cơ sở dữ liệu.</li>"
                    . "<li>Chủ động nghiên cứu và áp dụng các giải pháp công nghệ mới để giải quyết các bài toán kỹ thuật phức tạp.</li>"
                    . "</ul>",
                'qualifications' => "<ul>"
                    . "<li>Có kinh nghiệm làm việc thực tế ở vị trí {$title} hoặc tương đương.</li>"
                    . "<li>Nắm vững tư duy lập trình, kiến trúc hệ thống, Design Patterns và quản lý mã nguồn bằng Git.</li>"
                    . "<li>Thành thạo cơ sở dữ liệu (MySQL / PostgreSQL / MongoDB) và kỹ năng tối ưu truy vấn.</li>"
                    . "<li>Kỹ năng phân tích, tư duy logic tốt và khả năng giải quyết vấn đề độc lập cũng như làm việc nhóm hiệu quả.</li>"
                    . "<li>Tinh thần trách nhiệm cao, chủ động trong công việc và sẵn sàng học hỏi công nghệ mới.</li>"
                    . "</ul>",
                'benefits' => "<ul>"
                    . "<li>Mức thu nhập cạnh tranh tương xứng với năng lực (Thỏa thuận hấp dẫn), xét duyệt tăng lương định kỳ 1 - 2 lần/năm.</li>"
                    . "<li>Tháng lương thứ 13 cố định, thưởng dự án và thưởng hiệu quả kinh doanh cuối năm.</li>"
                    . "<li>Đóng đầy đủ BHXH, BHYT, BHTN theo đúng quy định của Nhà nước.</li>"
                    . "<li>Cung cấp đầy đủ máy móc, thiết bị làm việc hiện đại (MacBook/PC cấu hình cao, màn hình đôi).</li>"
                    . "<li>Chế độ du lịch hàng năm (Company Trip), teambuilding định kỳ, cà phê, đồ ăn nhẹ miễn phí tại văn phòng.</li>"
                    . "</ul>",
                'keywords' => !empty($skills) ? $skills : "PHP, Laravel, MySQL, RESTful API, Git, Docker, Clean Code",
                'source' => 'builtin'
            ];
        }

        // 2. Nhóm ngành Marketing / Truyền thông / Content
        if (preg_match('/(marketing|content|seo|copywriter|truyền thông|social|ads|quảng cáo|media)/i', $title)) {
            return [
                'salary' => '12 - 18 triệu VNĐ',
                'description' => "<p>Chúng tôi đang tìm kiếm vị trí <strong>{$title}</strong> (Cấp bậc: {$level}) nhiệt huyết và sáng tạo để mở rộng quy mô thương hiệu và tiếp cận khách hàng tiềm năng, trực tiếp đóng góp vào mục tiêu tăng trưởng kinh doanh của công ty.</p>",
                'responsibility' => "<ul>"
                    . "<li>Lập kế hoạch, triển khai và theo dõi các chiến dịch truyền thông/marketing đa kênh cho vị trí {$title}{$skillHint}.</li>"
                    . "<li>Sáng tạo nội dung hấp dẫn, thông điệp truyền thông sắc bén trên các kênh mạng xã hội, website và ấn phẩm quảng cáo.</li>"
                    . "<li>Theo dõi, đo lường các chỉ số hiệu quả chiến dịch (KPIs, ROI, CTR, Conversion Rate) và báo cáo đề xuất cải thiện.</li>"
                    . "<li>Nghiên cứu xu hướng thị trường, phân tích hành vi khách hàng mục tiêu và hoạt động của đối thủ cạnh tranh.</li>"
                    . "<li>Phối hợp chặt chẽ với team Thiết kế và Sales để đảm bảo tính đồng bộ của chiến dịch.</li>"
                    . "</ul>",
                'qualifications' => "<ul>"
                    . "<li>Kinh nghiệm làm việc từ 1 năm trở lên tại vị trí {$title} hoặc các chuyên ngành Marketing, Truyền thông tương đương.</li>"
                    . "<li>Khả năng viết lách tốt, tư duy thẩm mỹ cao và nhanh nhạy nắm bắt xu hướng (trends).</li>"
                    . "<li>Thành thạo các công cụ phân tích đo lường và quản trị kênh (Google Analytics, Ads Manager, SEO tools).</li>"
                    . "<li>Kỹ năng giao tiếp, thuyết trình tốt, có tinh thần làm việc nhóm và chịu được áp lực tiến độ.</li>"
                    . "</ul>",
                'benefits' => "<ul>"
                    . "<li>Mức lương cứng cạnh tranh + Thưởng KPI chiến dịch hấp dẫn không giới hạn theo hiệu quả công việc.</li>"
                    . "<li>Thưởng tháng 13, thưởng lễ Tết, sinh nhật và các ngày kỷ niệm của công ty.</li>"
                    . "<li>Môi trường làm việc trẻ trung, năng động, khuyến khích sáng tạo và thử nghiệm ý tưởng mới.</li>"
                    . "<li>Được đào tạo nâng cao kỹ năng chuyên môn từ các chuyên gia đầu ngành.</li>"
                    . "<li>Chế độ phúc lợi đầy đủ: BHXH, du lịch trong và ngoài nước, teambuilding hàng quý.</li>"
                    . "</ul>",
                'keywords' => !empty($skills) ? $skills : "Marketing, Content, SEO, Social Media, Google Ads, Facebook Ads, Kế hoạch truyền thông",
                'source' => 'builtin'
            ];
        }

        // 3. Nhóm ngành Kinh doanh / Bán hàng (Sales / Business Development)
        if (preg_match('/(sales|kinh doanh|bán hàng|tư vấn|account executive|telesales|bds|bất động sản)/i', $title)) {
            return [
                'salary' => '10 - 20 triệu VNĐ (Lương cứng + Hoa hồng)',
                'description' => "<p>Chúng tôi cần tuyển dụng vị trí <strong>{$title}</strong> (Cấp bậc: {$level}) năng động, đam mê kinh doanh để mở rộng mạng lưới khách hàng, gia tăng doanh số và khẳng định vị thế thương hiệu trên thị trường.</p>",
                'responsibility' => "<ul>"
                    . "<li>Chủ động tìm kiếm, tiếp cận và mở rộng tệp khách hàng tiềm năng theo định hướng công ty.</li>"
                    . "<li>Tư vấn giải pháp, giới thiệu sản phẩm/dịch vụ và đàm phán chốt hợp đồng với khách hàng.</li>"
                    . "<li>Chăm sóc, duy trì mối quan hệ bền vững với khách hàng hiện tại và xử lý các phản hồi sau bán hàng.</li>"
                    . "<li>Lập kế hoạch doanh số cá nhân theo tuần/tháng/quý và hoàn thành chỉ tiêu doanh thu được giao.</li>"
                    . "<li>Thu thập thông tin thị trường, đóng góp ý kiến cải tiến sản phẩm và chính sách bán hàng.</li>"
                    . "</ul>",
                'qualifications' => "<ul>"
                    . "<li>Kỹ năng giao tiếp, đàm phán và thuyết phục khách hàng xuất sắc; giọng nói truyền cảm, rõ ràng.</li>"
                    . "<li>Có tinh thần trách nhiệm cao, kiên trì, đam mê chinh phục mục tiêu doanh số.</li>"
                    . "<li>Ưu tiên ứng viên có kinh nghiệm trong ngành {$career} hoặc các lĩnh vực liên quan.</li>"
                    . "<li>Kỹ năng xử lý tình huống linh hoạt, khả năng làm việc độc lập và chịu áp lực công việc tốt.</li>"
                    . "</ul>",
                'benefits' => "<ul>"
                    . "<li>Thu nhập hấp dẫn: Lương cứng cố định + Hoa hồng doanh số cao + Thưởng nóng vượt chỉ tiêu (Không giới hạn).</li>"
                    . "<li>Được cung cấp data khách hàng tiềm năng và hỗ trợ đầy đủ công cụ làm việc.</li>"
                    . "<li>Lộ trình thăng tiến rõ ràng lên các vị trí Trưởng nhóm / Quản lý theo năng lực thực tế.</li>"
                    . "<li>Đầy đủ chế độ BHXH, BHYT, BHTN theo quy định nhà nước.</li>"
                    . "<li>Thưởng tháng 13, du lịch hàng năm, quà sinh nhật và các hoạt động teambuilding gắn kết.</li>"
                    . "</ul>",
                'keywords' => !empty($skills) ? $skills : "Kỹ năng bán hàng, Đàm phán, Chăm sóc khách hàng, Telesales, B2B, B2C, Thuyết phục",
                'source' => 'builtin'
            ];
        }

        // 4. Mặc định / Các ngành nghề khác
        return [
            'salary' => '10 - 15 triệu VNĐ (Thỏa thuận)',
            'description' => "<p>Công ty chúng tôi đang tìm kiếm nhân sự phù hợp cho vị trí <strong>{$title}</strong> (Cấp bậc: {$level}). Đây là cơ hội tuyệt vời để bạn phát huy tối đa năng lực chuyên môn, cùng đội ngũ chuyên nghiệp tạo dựng giá trị và đóng góp vào sự phát triển chung của tổ chức.</p>",
            'responsibility' => "<ul>"
                . "<li>Tiếp nhận, lập kế hoạch và trực tiếp thực hiện các nhiệm vụ chuyên môn theo vị trí {$title}{$skillHint}.</li>"
                . "<li>Đảm bảo tiến độ và chất lượng công việc được giao, tuân thủ đúng quy trình nghiệp vụ của phòng ban.</li>"
                . "<li>Phối hợp nhịp nhàng với các bộ phận liên quan để giải quyết các vấn đề phát sinh trong công việc hàng ngày.</li>"
                . "<li>Lập báo cáo định kỳ về kết quả công việc và đề xuất các giải pháp nâng cao hiệu suất làm việc.</li>"
                . "<li>Thực hiện các nhiệm vụ khác theo sự phân công của cấp trên quản lý trực tiếp.</li>"
                . "</ul>",
            'qualifications' => "<ul>"
                . "<li>Tốt nghiệp Cao đẳng/Đại học chuyên ngành phù hợp với vị trí công việc.</li>"
                . "<li>Có kinh nghiệm làm việc thực tế ở vị trí {$title} hoặc các lĩnh vực tương đương.</li>"
                . "<li>Kỹ năng giao tiếp tốt, tư duy logic, cẩn thận, tỉ mỉ và có tinh thần trách nhiệm cao.</li>"
                . "<li>Thành thạo tin học văn phòng và các phần mềm/công cụ phục vụ công việc.</li>"
                . "<li>Khả năng làm việc độc lập tốt và phối hợp hiệu quả trong môi trường làm việc nhóm.</li>"
                . "</ul>",
            'benefits' => "<ul>"
                . "<li>Mức lương cạnh tranh theo thỏa thuận (tương xứng năng lực và kinh nghiệm thực tế).</li>"
                . "<li>Chế độ xét duyệt tăng lương định kỳ hàng năm và thưởng theo kết quả kinh doanh.</li>"
                . "<li>Tháng lương thứ 13, thưởng các dịp lễ, Tết, sinh nhật và các chế độ hiếu hỷ.</li>"
                . "<li>Được tham gia đóng đầy đủ BHXH, BHYT, BHTN theo quy định của Luật Lao động.</li>"
                . "<li>Môi trường làm việc văn minh, thân thiện, đồng nghiệp cởi mở sẵn sàng hỗ trợ.</li>"
                . "<li>Tham gia các hoạt động teambuilding, du lịch nghỉ dưỡng hàng năm của công ty.</li>"
                . "</ul>",
            'keywords' => !empty($skills) ? $skills : "Kỹ năng chuyên môn, Làm việc nhóm, Tinh thần trách nhiệm, Giải quyết vấn đề",
            'source' => 'builtin'
        ];
    }
}

