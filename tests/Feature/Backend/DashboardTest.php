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
 * Time: 9:20 PM
 */

namespace Juzaweb\Tests\Feature\Backend;

use Illuminate\Support\Facades\Auth;
use Juzaweb\Core\Models\User;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::find(1);
        Auth::loginUsingId($this->user->id);
    }

    public function testRedirect()
    {
        $response = $this->get('/admin-cp');

        $response->assertStatus(302);
    }

    public function testIndex()
    {
        $response = $this->get('/admin-cp/dashboard');

        $response->assertStatus(200);
    }
}
