<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\ImageOptimizationService;
use Illuminate\Support\Facades\Storage;

class OptimizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:optimize {directory=products : Directory to optimize}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize all images in storage directory';

    /**
     * Image optimization service
     *
     * @var ImageOptimizationService
     */
    protected $imageService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(ImageOptimizationService $imageService)
    {
        parent::__construct();
        $this->imageService = $imageService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $directory = $this->argument('directory');

        $this->info("Starting image optimization for directory: $directory");

        $files = Storage::disk('public')->allFiles($directory);
        $optimizedCount = 0;

        $this->output->progressStart(count($files));

        foreach ($files as $file) {
            if ($this->isImage($file)) {
                try {
                    $this->imageService->optimizeImage($file, 1200, 80);
                    $optimizedCount++;
                    $this->output->progressAdvance();
                } catch (\Exception $e) {
                    $this->error("Failed to optimize $file: " . $e->getMessage());
                }
            } else {
                $this->output->progressAdvance();
            }
        }

        $this->output->progressFinish();

        $this->info("✅ Optimized $optimizedCount images!");

        return 0;
    }

    /**
     * Check if file is an image
     *
     * @param string $path
     * @return bool
     */
    private function isImage($path)
    {
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        return in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }
}
