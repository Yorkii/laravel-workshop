<?php namespace App\Managers\Contracts;

use App\Models\StaticPage;
use Illuminate\Database\Eloquent\Collection;

interface StaticPageManagerContract extends ManagerContract
{
    /**
     * @param int $page
     * @param int $perPage
     *
     * @return Collection
     */
    public function getAll($page = 1, $perPage = 10);

    /**
     * @param string $slug
     *
     * @return StaticPage
     */
    public function getBySlug($slug);

    /**
     * @param int $pageId
     *
     * @return StaticPage
     */
    public function getById($pageId);

    /**
     * @param int $slug
     *
     * @return bool
     */
    public function isSlugCorrect($slug);

    /**
     * @param array $data
     *
     * @return StaticPage
     */
    public function create(array $data);

    /**
     * @param int $staticPageId
     * @param array $data
     */
    public function update($staticPageId, array $data);

    /**
     * @param int $staticPageId
     */
    public function delete($staticPageId);
}
