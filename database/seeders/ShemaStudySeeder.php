<?php

namespace Database\Seeders;

use App\Models\Study;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ShemaStudySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $title = 'The Shema Series: Does "Elohim" Prove a Plural God?';
        $slug = Str::slug($title);

        $content = <<<'EOT'
<h3>Introduction</h3>
<p>One of the most significant verses in Scripture is the Shema found in Deuteronomy 6:4: "Hear, O Israel: The LORD our God is one LORD." This verse is the bedrock of faith for millions, yet it often sparks deep theological controversy—particularly regarding the nature of God.</p>

<p>In this study, we explore the Hebrew text to answer a critical question: Does the Hebrew word for God, Elohim, prove that God is a plural being?</p>

<h3>The Hidden Emphasis in the Hebrew Text</h3>
<p>In the original Hebrew scroll of the Shema, two letters are traditionally written larger or in bold: the Ayin (ע) in Shema (Hear) and the Dalet (ד) in Echad (One). Together, these letters form the word 'Ed (עד), meaning "witness" or "eternal." This subtle detail reminds us that Israel—and by extension, believers today—are called to be an eternal witness to the truth of God’s oneness.</p>

<h3>The Controversy: "Elohim" is Plural</h3>
<p>It is undeniably true that the Hebrew word Elohim (אֱלֹהִים) ends with the masculine plural suffix -im. Many theologians argue that this plurality in the noun suggests a plurality in God’s nature (such as the Trinity).</p>

<p>However, sound exegesis requires us to look at how the Bible uses this word in other contexts. Is Elohim always plural in meaning?</p>

<h3>The Plural of Majesty</h3>
<p>Scripture shows us that Elohim is often used to describe singular individuals to denote their greatness, authority, or majesty—a linguistic feature known as the "Plural of Majesty."</p>

<p>Consider these three biblical examples where Elohim refers to a single subject:</p>

<ul>
    <li><strong>Abraham (Genesis 23:6):</strong> The Hittites call Abraham a "mighty prince" (Elohim). They were not calling him "many gods," but honoring his great status.</li>
    <li><strong>Moses (Exodus 7:1):</strong> God tells Moses, "I have made thee a god (Elohim) to Pharaoh." Moses did not become a plural being; he was given superior authority over Pharaoh.</li>
    <li><strong>Nineveh (Jonah 3:3):</strong> Nineveh is described as an "exceedingly great city" (Elohim). It was one city, but vast in importance.</li>
</ul>

<h3>Conclusion: The Greatness of the One True God</h3>
<p>If Scripture uses Elohim to describe the greatness of a single man (Moses) or a single city (Nineveh), we must be careful not to force a plural theology onto God solely based on this word.</p>

<p>When the Bible calls the Father Elohim, it is not declaring that He is "more than one." Rather, it is declaring that He is the God of gods—the Almighty, Supreme Sovereign who is greater than all others. As Paul confirms in the New Testament:</p>

<blockquote>"But to us there is but one God, the Father, of whom are all things..." — 1 Corinthians 8:6</blockquote>
EOT;

        $excerpt = 'One of the most significant verses in Scripture is the Shema found in Deuteronomy 6:4. In this study, we explore the Hebrew text to answer a critical question: Does the Hebrew word for God, Elohim, prove that God is a plural being?';

        Study::updateOrCreate(
            ['slug' => $slug],
            [
                'title' => $title,
                'content' => $content,
                'excerpt' => $excerpt,
                'language' => 'english',
                'status' => 'published',
                'published_at' => now(),
                'meta_description' => 'Explore the meaning of Elohim in the Hebrew text. Does it prove a plural God? Discover the "Plural of Majesty" and the biblical context of Deuteronomy 6:4.',
            ]
        );
    }
}
