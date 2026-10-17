<?php

$outputPath = __DIR__ . '/LARAVEL_DEVELOPER_TECHNICAL_ASSESSMENT.docx';

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
    $align = !empty($opts['align']) ? '<w:jc w:val="' . $opts['align'] . '"/>' : '';
    $bulletText = !empty($opts['bullet']) ? '• ' : '';

    return '<w:p>
        <w:pPr>' . $spacing . $align . '</w:pPr>
        <w:r>
            <w:rPr>' . $bold . $size . $color . '</w:rPr>
            <w:t xml:space="preserve">' . htmlspecialchars($bulletText . $text, ENT_QUOTES | ENT_XML1) . '</w:t>
        </w:r>
    </w:p>';
}

function heading($num, $title) {
    return '<w:p>
        <w:pPr>
            <w:spacing w:before="180" w:after="60"/>
            <w:pBdr>
                <w:bottom w:val="single" w:sz="6" w:space="4" w:color="E5E7EB"/>
            </w:pBdr>
        </w:pPr>
        <w:r>
            <w:rPr><w:b/><w:sz w:val="24"/><w:color w:val="4F46E5"/></w:rPr>
            <w:t xml:space="preserve">' . $num . ' </w:t>
        </w:r>
        <w:r>
            <w:rPr><w:b/><w:sz w:val="24"/><w:color w:val="111827"/></w:rPr>
            <w:t xml:space="preserve">' . htmlspecialchars($title, ENT_QUOTES | ENT_XML1) . '</w:t>
        </w:r>
    </w:p>';
}

$body = '';

// Title & Header
$body .= '<w:p>
    <w:pPr><w:spacing w:after="30"/></w:pPr>
    <w:r>
        <w:rPr><w:b/><w:sz w:val="34"/><w:color w:val="111827"/></w:rPr>
        <w:t>Projukti Sheba</w:t>
    </w:r>
</w:p>';

$body .= '<w:p>
    <w:pPr>
        <w:spacing w:after="140"/>
        <w:pBdr>
            <w:bottom w:val="single" w:sz="16" w:space="6" w:color="4F46E5"/>
        </w:pBdr>
    </w:pPr>
    <w:r>
        <w:rPr><w:b/><w:sz w:val="22"/><w:color w:val="4F46E5"/></w:rPr>
        <w:t>ENGINEERING HIRING ASSESSMENT BRIEF</w:t>
    </w:r>
    <w:r>
        <w:rPr><w:sz w:val="20"/><w:color w:val="6B7280"/></w:rPr>
        <w:t xml:space="preserve">  |  Position: Mid-Level Laravel Engineer  |  Effort: 24–48 Hours</w:t>
    </w:r>
</w:p>';

// 01. Objective
$body .= heading('01.', 'Challenge Objective');
$body .= '<w:p>
    <w:pPr>
        <w:spacing w:before="40" w:after="120"/>
        <w:pBdr>
            <w:left w:val="single" w:sz="20" w:space="10" w:color="4F46E5"/>
        </w:pBdr>
        <w:shd w:val="clear" w:color="auto" w:fill="F9FAFB"/>
    </w:pPr>
    <w:r>
        <w:rPr><w:sz w:val="22"/><w:color w:val="374151"/></w:rPr>
        <w:t xml:space="preserve">Build a standalone, clean Laravel mini-application titled "Task &amp; Time Tracker". Demonstrate your software craftsmanship across database modeling, Eloquent relationships, state resilience, front-end interactivity, and clean architecture.</w:t>
    </w:r>
</w:p>';

// 02. Functional Requirements
$body .= heading('02.', 'Core Functional Requirements');

$body .= p('A. User Context & Auth', ['bold' => true, 'color' => '1F2937', 'size' => '22', 'spacing' => 40]);
$body .= p('Basic Authentication: Standard login & registration (e.g., Laravel Breeze or standard Auth).', ['bullet' => true, 'spacing' => 30]);
$body .= p('User Ownership: Tasks and logged work sessions must belong to the authenticated user.', ['bullet' => true, 'spacing' => 80]);

$body .= p('B. Task Checklist & Sub-Tasks', ['bold' => true, 'color' => '1F2937', 'size' => '22', 'spacing' => 40]);
$body .= p('Task Management: Users can create tasks and access a dedicated Task Detail View.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Checklist / Sub-Tasks: Inside a task, users can add and remove multiple checklist items (sub-tasks).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Seamless Updates: Checklist items can be marked complete/incomplete (seamless AJAX updates strongly preferred).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Progress Indicator: A dynamic progress indicator must automatically reflect the completion rate of checklist items.', ['bullet' => true, 'spacing' => 80]);

$body .= p('C. Live Stopwatch & Worklogs', ['bold' => true, 'color' => '1F2937', 'size' => '22', 'spacing' => 40]);
$body .= p('Interactive Timer: An interactive real-time stopwatch widget on the task view (Start, Pause, Stop & Save).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Resilience Requirement: The stopwatch must NOT lose elapsed time if the user refreshes or reloads the browser window.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Worklog Persistence: Stopping the timer prompts for a work note and saves duration into a persistent Worklogs table linked to the user & task.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Work History: A chronological history of recorded work sessions should be displayed on the task detail page.', ['bullet' => true, 'spacing' => 120]);

// 03. Architecture
$body .= heading('03.', 'Technical Architecture & Conventions');
$body .= p('Relational Design: Proper foreign keys connecting Users, Tasks, Checklists, and Worklogs.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Eloquent Models: Well-defined relationships (hasMany, belongsTo) and clean model encapsulation.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Front-End Interaction: Clean JavaScript with reliable state handling (localStorage).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Validation: Use dedicated Form Request classes for request validation.', ['bullet' => true, 'spacing' => 120]);

// 04. Bonus Expectations
$body .= heading('04.', 'Bonus / Standout Expectations');
$body .= p('Quick Filters: Quick filters on the task list (filter by status or title search).', ['bullet' => true, 'spacing' => 30]);
$body .= p('Seeders & Factories: Database Seeders / Factories for quick local testing.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Feature Testing: 1 or 2 Feature Tests (Pest / PHPUnit) verifying worklog recording or checklist toggling.', ['bullet' => true, 'spacing' => 120]);

// 05. Submission
$body .= heading('05.', 'Submission Instructions');
$body .= p('Repository: Push code to a public GitHub / GitLab repository.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Documentation: Include a concise README.md outlining setup steps.', ['bullet' => true, 'spacing' => 30]);
$body .= p('Delivery: Send the repository link to our hiring team upon completion.', ['bullet' => true, 'spacing' => 160]);

// Footer
$body .= '<w:p>
    <w:pPr>
        <w:spacing w:before="180" w:after="0"/>
        <w:pBdr>
            <w:top w:val="single" w:sz="6" w:space="6" w:color="E5E7EB"/>
        </w:pBdr>
    </w:pPr>
    <w:r>
        <w:rPr><w:sz w:val="18"/><w:color w:val="9CA3AF"/></w:rPr>
        <w:t>Projukti Sheba © Engineering Recruitment  •  Confidential Assessment Brief</w:t>
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

echo "DOCX updated successfully.\n";
