<?php

namespace Juzaweb\Core\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use Juzaweb\Core\Helpers\DatabaseManager;

class DatabaseController extends Controller
{
    /**
     * @var DatabaseManager
     */
    private $databaseManager;

    /**
     * @param DatabaseManager $databaseManager
     */
    public function __construct(DatabaseManager $databaseManager)
    {
        $this->databaseManager = $databaseManager;
    }

    /**
     * Migrate and seed the database.
     *
     * @return \Illuminate\View\View
     * @throws \Exception
     */
    public function database()
    {
        $response = $this->databaseManager->run();

        return redirect()->route('installer.admin')
                         ->with(['message' => $response]);
    }
}
