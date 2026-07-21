<?php

namespace App\Livewire\Forms;

use App\Models\KbArticle;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Validate;
use Livewire\Form;

class KbArticleForm extends Form
{
    public ?int $articleId = null;

    #[Validate]
    public string $title = '';

    #[Validate]
    public string $seo_link = '';

    #[Validate]
    public string $meta_description = '';

    #[Validate]
    public ?int $category_id = null;

    #[Validate]
    public string $article_content = '';

    #[Validate]
    public int $article_active = 1;

    #[Validate]
    public bool $show_date = true;

    #[Validate]
    public int $sort_order = 0;

    #[Validate]
    public int $kb_rating = 0;

    public function rules(): array
    {
        return [
            'title'            => ['required', 'string', 'max:255'],
            'seo_link'         => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9\-_]+$/i',
                Rule::unique('kb_articles', 'seo_link')->ignore($this->articleId),
            ],
            'meta_description' => ['nullable', 'string', 'max:1000'],
            'category_id'      => ['nullable', 'integer'],
            'article_content'  => ['required', 'string'],
            'article_active'   => ['required', 'integer', Rule::in([0, 1])],
            'show_date'        => ['required', 'boolean'],
            'sort_order'       => ['required', 'integer', 'min:0'],
            'kb_rating'        => ['required', 'integer'],
        ];
    }

    public function validationAttributes(): array
    {
        return [
            'seo_link' => 'SEO link (slug)',
            'article_active' => 'status',
            'kb_rating' => 'KB rating',
        ];
    }

    public function fillFromArticle(KbArticle $article): void
    {
        $this->articleId        = $article->id;
        $this->title            = $article->title;
        $this->seo_link         = $article->seo_link;
        $this->meta_description = $article->meta_description ?? '';
        $this->category_id      = $article->category_id;
        $this->article_content  = $article->article_content;
        $this->article_active   = $article->article_active;
        $this->show_date        = $article->show_date ?? true;
        $this->sort_order       = $article->sort_order ?? 0;
        $this->kb_rating        = $article->kb_rating ?? 0;
    }

    public function store(): KbArticle
    {
        $this->validate();

        $nowStr = now()->format('Y-m-d H:i:s');

        return KbArticle::create([
            'title'            => $this->title,
            'seo_link'         => $this->seo_link,
            'meta_description' => $this->meta_description ?: null,
            'category_id'      => $this->category_id,
            'article_content'  => $this->article_content,
            'article_active'   => $this->article_active,
            'show_date'        => $this->show_date,
            'sort_order'       => $this->sort_order,
            'kb_rating'        => $this->kb_rating,
            'date_added'       => $nowStr,
            'date_modified'    => $nowStr,
        ]);
    }

    public function update(KbArticle $article): void
    {
        $this->validate();

        $article->update([
            'title'            => $this->title,
            'seo_link'         => $this->seo_link,
            'meta_description' => $this->meta_description ?: null,
            'category_id'      => $this->category_id,
            'article_content'  => $this->article_content,
            'article_active'   => $this->article_active,
            'show_date'        => $this->show_date,
            'sort_order'       => $this->sort_order,
            'kb_rating'        => $this->kb_rating,
            'date_modified'    => now()->format('Y-m-d H:i:s'),
        ]);
    }
}
