<?php

namespace App\Controller;

use App\Entity\Instance;
use Carbon\Carbon;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\Style\Font;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Zend\Escaper\Escaper;

class ExportController extends AbstractController
{
    /**
     * @Route("/instance/{instance}/export", name="export")
     */
    public function index(Instance $instance)
    {
        return $this->render('export/index.html.twig', [
            'instance' => $instance
        ]);
    }

    /**
     * @Route("/instance/{instance}/export/events.docx", name="export_events_doc")
     */
    public function exportEventsToDoc(Instance $instance)
    {
        $phpWord = new PhpWord();

        $section = $phpWord->addSection(['breakType' => 'continuous']);
        $section->addText("Πρόχειρο Πρόγραμμα {$instance->getName()}.\n");
        $section->addText("Τελευταία ενημέρωση: " . Carbon::now()->toRfc822String());
        $section->addText("------------------\n\n<hr />\n\n");

        // Set up the styles
        $bold = (new Font())->setBold(true)->setName('Bold');
        $emph = (new Font())->setItalic(true)->setName('Emphasis');

        $escaper = new Escaper();

        foreach ($instance->getEvents() as $event) {
            $section = $phpWord->addSection(['breakType' => 'continuous']);
//            $section->addTextRun();
            $section->addText("Δράση #{$event->getId()}", $emph);
            $section->addText("Τίτλος: ", $bold);
            $section->addText($escaper->escapeHtml($event->getTitle()));

            if (!empty($event->getTeam())) {
                $section->addText("\nΟμάδα: ", $bold);
                $section->addText($escaper->escapeHtml($event->getTeam()));
            }

            $section->addText("\nΥπεύθυνοι καθηγητές: ", $bold);
            foreach ($event->getSubmitters() as $submitter) {
                if (!$submitter->getHidden()) {
                    $section->addListItem($escaper->escapeHtml($submitter->describeQuickly()));
                }
            }

            $section->addText("\nΗλικίες: ", $bold);
            $section->addText($event->getDataAsObject()->audience ?? '');

            $section->addText("\nΚατηγορίες: ", $bold);
            foreach (($event->getDataAsObject()->categories ?? []) as $category) {
                // TODO: Use i18n
                $cat = null;
                switch($category) {
                    case 'experiment':
                        $cat = "Πείραμα";
                        break;
                    case 'observation':
                        $cat = "Παρατήρηση";
                        break;
                    case 'lab':
                        $cat = "Δημιουργικό εργαστήριο";
                        break;
                    case 'presentation':
                        $cat = "Παρουσίαση";
                        break;
                    case 'demonstration':
                        $cat = "Επίδειξη";
                        break;
                }

                if (!$cat) continue;

                $section->addListItem($cat);
            }

            $section->addText("\nΏρες διεξαγωγής: ", $bold);
            if ($event->getRepetitions()->count() <= 4) {
                foreach ($event->getRepetitions() as $repetition) {
                    $section->addListItem(
                        $repetition->getDate()->format('H:i')
                        . ' – '
                        . $repetition->getEndTime()->format('H:i')
                    );
                }
            } else {
                $rep1 = $event->getRepetitions()[0];
                $rep2 = $event->getRepetitions()[1];
                $repl = $event->getRepetitions()->last();

                $diff = $event->getRepetitions()[1]->getDate()->diffInMinutes($event->getRepetitions()[0]->getDate());

                $section->addListItem($rep1->getDate()->format('H:i')
                    . ' – '
                    . $repl->getEndTime()->format('H:i')
                    . ", κάθε {$diff} λεπτά");
            }

//            $section->addLine(['weight' => 2, 'width' => 600, 'height' => 2]);
            $section->addText("----------------------------------\n");
        }

        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save('outpute.docx');
        return $this->file('outpute.docx');

    }
}
