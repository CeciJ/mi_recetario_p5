<?php

namespace App\Listener;

use App\Entity\MealPlanning;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use App\Repository\MealPlanningRepository;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarListener
{
    private $mealPlanningRepository;
    private $router;

    public function __construct(
        MealPlanningRepository $mealPlanningRepository,
        UrlGeneratorInterface $router
    ) {
        $this->mealPlanningRepository = $mealPlanningRepository;
        $this->router = $router;
    }

    public function load(CalendarEvent $calendar): void
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        $mealPlannings = $this->mealPlanningRepository
            ->createQueryBuilder('mealPlanning')
            ->where('mealPlanning.beginAt BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($mealPlannings as $mealPlanning) {
            $mealPlanningEvent = new Event(
                $mealPlanning->getTitle(),
                $mealPlanning->getBeginAt(),
                $mealPlanning->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $mealPlanningEvent->setOptions([
                'backgroundColor' => '#fbac03',
                'borderColor' => '#fbac03',
            ]);
            $mealPlanningEvent->addOption(
                'url',
                $this->router->generate('meal_planning.show', [
                    'id' => $mealPlanning->getId(),
                ])
                
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($mealPlanningEvent);
        }
    }
}