<?php

namespace App\Command;

use App\Entity\Ancestry\AncestralFeature;
use App\Entity\Ancestry\Ancestry;
use App\Entity\Ancestry\Heritage;
use App\Entity\Core\Feat;
use App\Entity\Core\Release;
use App\Entity\Setting\Background;
use App\Entity\Setting\Culture;
use App\Entity\Setting\Language;
use App\Repository\Ancestry\AncestryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\LockableTrait;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class ReleaseContentCommand extends Command
{
    use LockableTrait;

    protected static $defaultName = 'content:release';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Aktywuje zawartość przygotowaną do wydania.')
            ->setHelp('Aktywuje zawartość przygotowaną do wydania.')
            ->addOption('apply', null, InputOption::VALUE_NONE, 'Czy zapisać zmiany? (flush)')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if (!$this->lock()) {
            $output->writeln('Ta komenda jest już wykonywana w innym procesie.');

            return 0;
        }

        $output->writeln('Rozpoczynanie wydania!');

        $apply = $input->getOption('apply');

        if ($apply) {
            $output->writeln('Zmiany będą zapisane!');
        } else {
            $output->writeln('Zmiany NIE będą zapisane!');
        }

        $helper = $this->getHelper('question');

        $nameQuestion = new Question('Podaj nazwę nowego wydania:');
        $releaseName = $helper->ask($input, $output, $nameQuestion);

        $versionQuestion = new Question('Podaj number nowej wersji zawartości:');
        $contentVersion = $helper->ask($input, $output, $versionQuestion);

        $release = new Release();
        $release->setName($releaseName);
        $release->setContentVersion($contentVersion);

        $releasedDataArray = [];

        /* wydanie ras */

        $ancestryRepository = $this->em->getRepository(Ancestry::class);

        $ancestriesToBeReleased = $ancestryRepository->getAncestriesForRelease();

        if ($ancestriesToBeReleased) {
            foreach ($ancestriesToBeReleased as $ancestry) {
                $ancestry->setIsActive(true);
                $releasedDataArray['ancestry'][$ancestry->getId()] = $ancestry->getName();
                $ancestry->setRelease($release);
                $this->em->persist($ancestry);
            }
        }

        /* wydanie dziedzictw */

        $heritageRepository = $this->em->getRepository(Heritage::class);

        $heritagesToBeReleased = $heritageRepository->getHeritagesForRelease();

        if ($heritagesToBeReleased) {
            foreach ($heritagesToBeReleased as $heritage) {
                $heritage->setIsActive(true);
                $releasedDataArray['heritage'][$heritage->getId()] = $heritage->getName();
                $heritage->setRelease($release);
                $this->em->persist($heritage);
            }
        }

        /* wydanie kultur */

        $cultureRepository = $this->em->getRepository(Culture::class);

        $culturesToBeReleased = $cultureRepository->getCulturesForRelease();

        if ($culturesToBeReleased) {
            foreach ($culturesToBeReleased as $culture) {
                $culture->setIsActive(true);
                $releasedDataArray['culture'][$culture->getId()] = $culture->getName();
                $culture->setRelease($release);
                $this->em->persist($culture);
            }
        }

        /* wydanie atutów */

        $featRepository = $this->em->getRepository(Feat::class);

        $featsToBeReleased = $featRepository->getFeatsForRelease();

        if ($featsToBeReleased) {
            foreach ($featsToBeReleased as $feat) {
                $feat->setIsActive(true);
                $releasedDataArray['feat'][$feat->getId()] = $feat->getName();
                $feat->setRelease($release);
                $this->em->persist($feat);
            }
        }

        /* wydanie pochodzeń */

        $backgroundRepository = $this->em->getRepository(Background::class);

        $backgroundsToBeReleased = $backgroundRepository->getBackgroundsForRelease();

        if ($backgroundsToBeReleased) {
            foreach ($backgroundsToBeReleased as $background) {
                $background->setIsActive(true);
                $releasedDataArray['background'][$background->getId()] = $background->getName();
                $background->setRelease($release);
                $this->em->persist($background);
            }
        }

        /* wydanie języków */

        $languageRepository = $this->em->getRepository(Language::class);

        $languagesToBeReleased = $languageRepository->getLanguagesForRelease();

        if ($languagesToBeReleased) {
            foreach ($languagesToBeReleased as $language) {
                $language->setIsActive(true);
                $releasedDataArray['language'][$language->getId()] = $language->getName();
                $language->setRelease($release);
                $this->em->persist($language);
            }
        }

        /* finalizacja */

        $release->setContentReleased($releasedDataArray);

        $this->em->persist($release);

        if ($apply) {
            $this->em->flush();
        }

        $this->release();
        $output->write('Gotowe!');

        return 1;
    }
}