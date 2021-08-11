<?php
/**
 * MYMO CMS - Free Laravel CMS
 *
 * @package    juzawebcms/juzawebcms
 * @author     The Anh Dang <dangtheanh16@gmail.com>
 * @link       https://github.com/juzawebcms/juzawebcms
 * @license    MIT
 *
 * Created by The Anh.
 * Date: 6/19/2021
 * Time: 10:34 PM
 */

namespace Juzaweb\Tests\Feature\Backend;

use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Facades\HookAction;
use Juzaweb\Core\Models\User;
use Juzaweb\PostType\PostType;
use Tests\TestCase;

class PostTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::find(1);
        Auth::loginUsingId($this->user->id);
    }

    public function testIndex()
    {
        $response = $this->get('/admin-cp/posts');

        $response->assertStatus(200);
    }

    public function testCreate()
    {
        $response = $this->get('/admin-cp/posts/create');

        $response->assertStatus(200);
    }


}