<?php

namespace Juzaweb\Core\Http\Controllers\Installer;

use Illuminate\Routing\Controller;
use Juzaweb\Core\Events\InstallerFinished;
use Juzaweb\Core\Helpers\FinalInstallManager;
use Juzaweb\Core\Helpers\InstalledFileManager;

class FinalController extends Controller
{
    /**
     * Update installed file and display finished view.
     *
     * @param InstalledFileManager $fileManager
     * @param FinalInstallManager $finalInstall
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Throwable
     */
    public function finish (
        InstalledFileManager $fileManager,
        FinalInstallManager $finalInstall
    )
    {
        $finalMessages = $finalInstall->runFinal();
        $finalStatusMessage = $fileManager->update();

        event(new InstallerFinished());

        return view('juzaweb::installer.finished', compact(
            'finalMessages',
            'finalStatusMessage'
        ));
    }
}
