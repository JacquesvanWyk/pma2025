<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Pioneer Missions Africa - Core Theological Beliefs
    |--------------------------------------------------------------------------
    |
    | These beliefs guide the AI in generating biblically accurate content
    | that aligns with Pioneer Adventist theology and PMA's mission.
    |
    */

    'beliefs' => [
        'godhead' => [
            'position' => 'non-trinitarian',
            'description' => 'We believe in the one true God (the Father) and His Son Jesus Christ as two distinct, separate beings. The Father is the only true God, and Jesus is His literal Son.',
            'key_points' => [
                'The Father is the supreme God',
                'Jesus Christ is the literal Son of God',
                'The Father and Son are distinct persons, not coequal',
                'The Holy Spirit is the power and presence of God, not a third person',
            ],
        ],

        'pioneer_heritage' => [
            'description' => 'We uphold the original doctrinal positions of the Seventh-day Adventist pioneers before the adoption of the Trinity doctrine.',
            'emphasis' => [
                'Early Adventist pioneer teachings',
                'Original understanding of the Godhead',
                'Transition away from truth in modern Adventism',
                'Return to biblical foundations',
            ],
        ],

        'everlasting_gospel' => [
            'description' => 'We proclaim the Everlasting Gospel where the one true God sent His real Son to die on our behalf.',
            'focus' => 'Present truth for the end times, biblical accuracy, and the character of God',
        ],

        'ellen_white' => [
            'position' => 'Valued within pioneer-era Adventism context',
            'note' => 'Referenced for her early writings that aligned with pioneer theology',
        ],

        'sabbath' => [
            'position' => 'The seventh-day Sabbath (Saturday) is God\'s holy day of rest',
            'emphasis' => 'Biblical Sabbath observance from Friday sunset to Saturday sunset',
        ],

        'health' => [
            'position' => 'Both physical and spiritual health are important',
            'emphasis' => 'Holistic wellness as part of Christian living',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Formatting Guidelines
    |--------------------------------------------------------------------------
    |
    | These rules ensure consistent, readable, and well-structured content.
    |
    */

    'formatting' => [
        'structure' => [
            'opening' => 'Start with a 2-3 sentence engaging hook (NO heading)',
            'sections' => 'Use ## for main headings, ### for subsections',
            'conclusion' => 'End with a clear call to action or invitation to study further',
        ],

        'paragraphs' => [
            'length' => '3-5 sentences maximum for readability',
            'spacing' => 'Add blank lines between ALL paragraphs and sections',
            'style' => 'Clear, simple language appropriate for all audiences',
        ],

        'lists' => [
            'type' => 'Use bullet points (-) for lists, avoid numbered lists',
            'spacing' => 'Add space between list items for readability',
        ],

        'emphasis' => [
            'bold' => 'Use **term** for important theological terms',
            'blockquotes' => 'Use > for Bible quotes and key truths',
            'links' => 'Scripture references in parentheses: (John 3:16)',
        ],

        'headings' => [
            'h2' => 'Main sections (##) - Introduction, Biblical Teaching, Application, Conclusion',
            'h3' => 'Subsections (###) - Specific points within main sections',
            'spacing' => 'Generous spacing above and below headings',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Study Content Structure Templates
    |--------------------------------------------------------------------------
    */

    'study_structure' => [
        'beginner' => [
            'sections' => ['Introduction', 'Simple Explanation', 'Key Bible Verses', 'How This Affects You', 'Prayer and Reflection'],
            'tone' => 'Simple, encouraging, foundational',
            'length' => 'Short paragraphs, lots of examples',
        ],

        'intermediate' => [
            'sections' => ['Introduction', 'Key Bible Verses', 'Biblical Teaching', 'Pioneer Perspective', 'Practical Application', 'Discussion Questions', 'Conclusion'],
            'tone' => 'Detailed but accessible, theologically sound',
            'length' => 'Medium paragraphs, balanced depth',
        ],

        'advanced' => [
            'sections' => ['Introduction', 'Historical Context', 'Deep Biblical Analysis', 'Pioneer Theology', 'Greek/Hebrew Insights', 'Theological Implications', 'Application', 'Conclusion'],
            'tone' => 'Scholarly, thorough, precise',
            'length' => 'Longer paragraphs, extensive references',
        ],

        'youth' => [
            'sections' => ['Why This Matters to You', 'The Story', 'Bible Proof', 'Real Life Application', 'Challenge Questions', 'Take Action'],
            'tone' => 'Engaging, relatable, modern language',
            'length' => 'Very short paragraphs, conversational',
        ],

        'sermon' => [
            'sections' => ['Opening Illustration', 'Main Text', 'Points (3-5)', 'Supporting Scriptures', 'Application', 'Altar Call'],
            'tone' => 'Preaching style, compelling, direct',
            'length' => 'Outline format with talking points',
        ],

        'tract' => [
            'sections' => ['Hook', 'Main Truth', 'Bible Support', 'Call to Action'],
            'tone' => 'Concise, evangelistic, inviting',
            'length' => '300-500 words total',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Image Generation Settings
    |--------------------------------------------------------------------------
    */

    'images' => [
        'enabled' => true,
        'provider' => 'gemini', // gemini (free) or openai (paid) - now using Nano Banana via Gemini provider
        'model' => 'gemini-2.5-flash-image', // Gemini 2.5 Flash Image model

        'prompt_template' => 'Create a reverent, educational biblical illustration for a study about {topic}. Style: Seventh-day Adventist church materials, appropriate for all ages, respectful, inspiring. Avoid: Trinity symbolism, Catholic imagery. Include: Biblical accuracy, light, hope, truth.',

        'settings' => [
            'size' => '1024x1024',
            'quality' => 'standard',
            'n' => 1, // number of images
        ],

        'storage' => [
            'disk' => 'public',
            'path' => 'studies/images',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | AI Model Settings
    |--------------------------------------------------------------------------
    */

    'models' => [
        'text' => [
            'provider' => 'gemini',
            'model' => 'gemini-2.5-flash',
            'temperature' => 0.7, // Creativity vs consistency (0-1)
            'max_tokens' => 4000,
        ],

        'fallback' => [
            'provider' => 'gemini',
            'model' => 'gemini-1.5-flash',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Content Quality Guidelines
    |--------------------------------------------------------------------------
    */

    'quality' => [
        'theological_accuracy' => [
            'Father and Son are distinct beings',
            'Avoid Trinity language (coequal, three persons in one)',
            'Use Pioneer Adventist terminology',
            'Biblical references must be accurate',
        ],

        'language_style' => [
            'Clear and accessible',
            'Avoid theological jargon unless explained',
            'Positive and hopeful tone',
            'Respectful of all readers',
        ],

        'scripture_usage' => [
            'Always cite book, chapter, and verse',
            'Use multiple translations when helpful',
            'Provide context for verses',
            'Quote accurately',
        ],
    ],

];
