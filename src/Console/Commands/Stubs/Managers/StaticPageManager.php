<?php namespace App\Managers;

use App\Managers\Contracts\StaticPageManagerContract;
use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Collection;

class StaticPageManager extends Manager implements StaticPageManagerContract
{
    /**
     * @param int $page
     * @param int $perPage
     *
     * @return Collection
     */
    public function getAll($page = 1, $perPage = 10)
    {
        $skip = ($page - 1) * $perPage;

        return $this->getStaticPageRepository()
            ->getModel()
            ->skip($skip)
            ->take($perPage)
            ->orderBy('slug', 'ASC')
            ->get();
    }

    /**
     * @param string $slug
     *
     * @return StaticPage
     */
    public function getBySlug($slug)
    {
        return $this->getStaticPageRepository()
            ->getModel()
            ->where('slug', strtolower($slug))
            ->first();
    }

    /**
     * @param int $pageId
     *
     * @return StaticPage
     */
    public function getById($pageId)
    {
        return $this->getStaticPageRepository()->find($pageId);
    }

    /**
     * @param int $slug
     *
     * @return bool
     */
    public function isSlugCorrect($slug)
    {
        return !preg_match('/([^a-z0-9\-]+)/i', $slug);
    }

    /**
     * @param array $data
     *
     * @return StaticPage
     */
    public function create(array $data)
    {
        return $this->getStaticPageRepository()->create($data);
    }

    /**
     * @param int $staticPageId
     * @param array $data
     */
    public function update($staticPageId, array $data)
    {
        $this->getStaticPageRepository()->update($staticPageId, $data);
    }

    /**
     * @param int $staticPageId
     */
    public function delete($staticPageId)
    {
        $this->getStaticPageRepository()->delete($staticPageId);
    }
}