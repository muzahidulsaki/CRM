<?php

$outputPath = __DIR__ . '/META_WEBHOOK_LEAD_ASSESSMENT.docx';

$zip = new ZipArchive();
if ($zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Cannot create docx file at $outputPath\n");
}

// 1. [Content_Types].xml
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
    <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>';
$zip->addFromString('[Content_Types].xml', $contentTypes);

// 2. _rels/.rels
$rootRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';
$zip->addFromString('_rels/.rels', $rootRels);

// 3. word/_rels/document.xml.rels
$docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
$zip->addFromString('word/_rels/document.xml.rels', $docRels);

// 4. word/styles.xml
$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:docDefaults>
        <w:rPrDefault>
            <w:rPr>
                <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                <w:sz w:val="22"/>
                <w:color w:val="1F2937"/>
            </w:rPr>
        </w:rPrDefault>
        <w:pPrDefault>
            <w:pPr>
                <w:spacing w:after="100" w:line="250" w:lineRule="auto"/>
            </w:pPr>
        </w:pPrDefault>
    </w:docDefaults>
</w:styles>';
$zip->addFromString('word/styles.xml', $styles);

function p($text, $opts = []) {
    $bold = !empty($opts['bold']) ? '<w:b/>' : '';
    $size = !empty($opts['size']) ? '<w:sz w:val="' . $opts['size'] . '"/>' : '';
    $color = !empty($opts['color']) ? '<w:color w:val="' . $opts['color'] . '"/>' : '';
    $spacing = isset($opts['spacing']) ? '<w:spacing w:after="' . $opts['spacing'] . '" w:before="' . ($opts['spacingBefore'] ?? 0) . '"/>' : '';
    $bulletText = !empty($opts['bullet']) ? '• ' : '';

    return '<w:p>
        <w:pPr>' . $spacing . '</w:pPr>
        <w:r>
            <w:rPr>' . $bold . $size . $color . '</w:rPr>
            <w:t xml:space="preserve">' . htmlspecialchars($bulletText . $text, ENT_QUOTES | ENT_XML1) . '</w:t>
        </w:r>
    </w:p>';
}

function heading($num, $title) {
    return '<w:p>
        <w:pPr>
            <w:spacing w:before="160" w:after="60"/>
            <w:pBdr>
                <w:bottom w:val="single" w:sz="6" w:space="4" w:color="E2E8F0"/>
            </w:pBdr>
        </w:pPr>
        <w:r>
            <w:rPr><w:b/><w:sz w:val="24"/><w:color w:val="2563EB"/></w:rPr>
            <w:t xml:space="preserve">' . $num . ' </w:t>
        </w:r>
        <w:r>
            <w:rPr><w:b/><w:sz w:val="24"/><w:color w:val="0F172A"/></w:rPr>
            <w:t xml:space="preserve">' . htmlspecialchars($title, ENT_QUOTES | ENT_XML1) . '</w:t>
        </w:r>
    </w:p>';
}

$body = '';

// Title & Header
$body .= '<w:p>
    <w:pPr><w:spacing w:after="30"/></w:pPr>
    <w:r>
        <w:rPr><w:b/><w:sz w:val="34"/><w:color w:val="0F172A"/></w:rPr>
        <w:t>Projukti Sheba</w:t>
    </w:r>
</w:p>';

$body .= '<w:p>
    <w:pPr>
        <w:spacing w:after="140"/>
        <w:pBdr>
            <w:bottom w:val="single" w:sz="16" w:space="6" w:color="2563EB"/>
        </w:pBdr>
    </w:pPr>
    <w:r>
        <w:rPr><w:b/><w:sz w:val="22"/><w:color w:val="2563EB"/></w:rPr>
        <w:t>ENGINEERING HIRING ASSESSMENT BRIEF</w:t>
    </w:r>
    <w:r>
        <w:rPr><w:sz w:val="20"/><w:color w:val="64748B"/></w:rPr>
        <w:t xml:space="preserve">  |  Position: Mid-Level Laravel / Backend Engineer  |  Effort: 24–48 Hours</w:t>
    </w:r>
</w:p>';

// 01. Objective
$body .= heading('01.', 'Challenge Objective');
$body .= '<w:p>
    <w:pPr>
        <w:spacing w:before="40" w:after="120"/>
        <w:pBdr>
            <w:left w:val="single" w:sz="20" w:space="10" w:color="2563EB"/>
        </w:pBdr>
        <w:shd w:val="clear" w:color="auto" w:fill="F8FAFC"/>
    </w:pPr>
    <w:r>
        <w:rPr><w:sz w:val="22"/><w:color w:val="334155"/></w:rPr>
        <w:t xml:space="preserve">Build a standalone Laravel application titled "Meta Webhook &amp; Automated Lead Ingestion Engine". The goal is to automatically capture customer inquiries from Meta (Facebook Messenger &amp; Lead Ads), verify payload authenticity, prevent duplicates, distribute leads across sales agents, and view them on a clean management dashboard.</w:t>
    </w:r>
</w:p>';

// 02. Functional Requirements
$body .= heading('02.', 'Core Functional Requirements');

$body .= p('A. Meta Webhook Handshake & Verification (GET)', ['bold' => true, 'color' => '1E293B', 'size' => '22', 'spacing' => 40]);
$body .= p('Implement the Meta Webhook Verification endpoint: GET /api/v1/webhooks/meta.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Validate hub.mode and hub.verify_token against a configured secret in .env.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Return the exact hub.challenge string with HTTP 200 OK when valid; return HTTP 403 Forbidden on mismatch.', ['bullet' => true, 'spacing' => 80]);

$body .= p('B. Secure Payload Ingestion & Signature (POST)', ['bold' => true, 'color' => '1E293B', 'size' => '22', 'spacing' => 40]);
$body .= p('Handle incoming event notifications: POST /api/v1/webhooks/meta.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Security: Verify the X-Hub-Signature-256 header using HMAC-SHA256 with the app secret to guarantee genuine Meta delivery.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Parse incoming customer data (Sender ID, Name, Phone/Email, Message text, or Lead Ad form fields).', ['bullet' => true, 'spacing' => 80]);

$body .= p('C. Anti-Duplicate (Idempotency) Logic', ['bold' => true, 'color' => '1E293B', 'size' => '22', 'spacing' => 40]);
$body .= p('If the same customer (matching phone, email, or sender ID) sends multiple messages within 24 hours, do NOT create duplicate leads.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Instead, append the new message as an Activity Note under the existing lead record.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Ensure data integrity using Eloquent relationships & database transactions.', ['bullet' => true, 'spacing' => 80]);

$body .= p('D. Round-Robin Lead Assignment & Dashboard', ['bold' => true, 'color' => '1E293B', 'size' => '22', 'spacing' => 40]);
$body .= p('Round-Robin Allocation: Auto-assign newly created leads evenly among active sales agents (users with a Sales role).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Lead Explorer Dashboard: A clean UI displaying incoming leads, source badges (Messenger / Lead Ad), assigned agent, and activity logs.', ['bullet' => true, 'spacing' => 120]);

// 03. Architecture
$body .= heading('03.', 'Architecture & Conventions');
$body .= p('Environment Configuration: META_VERIFY_TOKEN and META_APP_SECRET in .env.example.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Service Layer: Separate webhook handling into dedicated Service classes (e.g., MetaWebhookService) to keep controllers thin.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Response Discipline: Always acknowledge valid webhooks immediately with HTTP 200 {"status": "EVENT_RECEIVED"}.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Database Design: Relational schema linking Leads, LeadActivities, and Assigned Users.', ['bullet' => true, 'spacing' => 120]);

// 04. Bonus Expectations
$body .= heading('04.', 'Bonus / Standout Expectations');
$body .= p('Queued Processing: Dispatch webhook payload to a background Queue Job to respond within <200ms.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Mock Webhook Tester: Provide a sample cURL command or Postman collection with realistic Meta JSON payloads for testing.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Automated Feature Test: 1 or 2 PHPUnit/Pest tests simulating valid and invalid webhook requests.', ['bullet' => true, 'spacing' => 120]);

// 05. Submission
$body .= heading('05.', 'Submission Instructions');
$body .= p('Repository: Push code to a public GitHub / GitLab repository with clear, iterative commit history.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Documentation: Include a comprehensive README.md with setup instructions, migration steps, and sample cURL test payloads.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Delivery: Share the repository URL with our hiring team upon completion.', ['bullet' => true, 'spacing' => 160]);

// Footer
$body .= '<w:p>
    <w:pPr>
        <w:spacing w:before="180" w:after="0"/>
        <w:pBdr>
            <w:top w:val="single" w:sz="6" w:space="6" w:color="E2E8F0"/>
        </w:pBdr>
    </w:pPr>
    <w:r>
        <w:rPr><w:sz w:val="18"/><w:color w:val="94A3B8"/></w:rPr>
        <w:t>Projukti Sheba © Engineering Recruitment  •  Confidential Assessment Brief  •  Meta Webhook Lead Automation</w:t>
    </w:r>
</w:p>';

$documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:body>
        ' . $body . '
        <w:sectPr>
            <w:pgSz w:w="11906" w:h="16838"/>
            <w:pgMar w:top="1134" w:right="1134" w:bottom="1134" w:left="1134" w:header="708" w:footer="708" w:gutter="0"/>
        </w:sectPr>
    </w:body>
</w:document>';

$zip->addFromString('word/document.xml', $documentXml);
$zip->close();

echo "DOCX created successfully.\n";
