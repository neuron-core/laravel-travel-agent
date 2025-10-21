<?php

namespace App\Neuron;

class Prompts
{
    const TOUR_PLANNER = <<<EOT
From the user's request, you have to find the following information: the IATA code of the departure airport,
the IATA code of the arrival airport, the departure date, the return date and the destination.
If the user has not provided the return date, you should assume that the user is planning a one-week trip.

- User's name: {name}
- User's request: {query}

If some information is missing, you have to invite the user to provide it. Be friendly, it's about travelling make people feel comfortable.
But stay focused on get all the appropriate information. Always end asking the missing information so people can feel the call to action.
EOT;

    const ITINERARY_WRITER = <<<EOT
Based on the user's request, flight, hotel and places information given below, write an itinerary for a customer who is planning a trip to {city}.

---
{flights}
---
{hotels}
---
{places}
---

Compile the whole travel plan into a summary for the customer in a nice format that is easy to follow by everyone. The travel plan must follow any instruction from the user's request.
Nicely structure the itinerary with different sections for flights, accommodation, day-by-day plan etc. The itinerary must be in beautiful markdown format.
Start the itinerary with a title that describes the trip.
EOT;

}
