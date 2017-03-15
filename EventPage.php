<?php

/**
 * EventPage 
 *
 * Helper class for handling RRule Occurrence Events
 * Stores properties for easier access and holds a reference to the original page.
 *
 * @method private void initDateProps(DateTime $event)
 *
 */

class EventPage{
	
	/**
     * Reference to the original page;
     *
     * @var Page
     *
     */
	public $page = NULL;
	
	/**
     * List of occurrences defined by the rrule on the original page
     *
     * @var string
     *
     */
	public $dateList = "";
	
	/**
     * Name of the week day, formatted using the week_days constant;
     *
     * @var string
     *
     */
	public $dayName = "";
	
	/**
     * Name of the Month, Capitalized
     *
     * @var string
     *
     */
	public $dayNumber = "";
	
	/**
     * Name of the Month, Capitalized
     *
     * @var string
     *
     */
	public $monthName = "";
	
	/**
     * Month Number, no leading zeros
     *
     * @var string
     *
     */
	public $monthNumber = "";
	
	/**
     * Four digit representation of the year.
     *
     * @var string
     *
     */
	public $year = "";
	
	/**
     * Number of times the event has repeated, including this one
     *
     * @var int
     *
     */
	public $repeatCount = 0;
	
	/**
     * Is this the original occurrence?
     *
     * @var bool
     *
     */
	public $isOriginal = false;
	
	/**
     * Start datetime object set by the RRule, does not necessarily equal the first occurrence
     *
     * @var DateTime
     *
     */
	public $dateStart = NULL;
	
	/**
     * Datetime object set for ending the reccurrence by the RRule, dos not necessarily equal the last occurrence
     *
     * @var DateTime
     *
     */
	public $dateUntil = NULL;
	
	/**
     * Recurrence Frequency set by the RRule
     *
     * @var String
     *
     */
	public $frequency = 0;
	
	/**
     * Recurrence Interval set by the RRule
     *
     * @var String
     *
     */
	public $interval = 1;
	
	/**
     * Total number of occurrences in the RRule
     *
     * @var int
     *
     */
	public $count = 0;
	
	/**
     * Array of occurrences as defined by the RRule
     *
     * @var int
     *
     */
	public $dateList = 0;
	
	/**
     * The RRule Object from which the EventPage was made
     *
     * @var RRule
     *
     */
	public $rrule = null;
	
	/**
     * The RRule String from which the EventPage was made
     *
     * @var String
     *
     */
	public $rruleToText = "";
	
	/**
     * Array representing the weekdays as numbers
     *
     */
	const week_days = array(
		1 => 'mo',
		2 => 'tu',
		3 => 'we',
		4 => 'th',
		5 => 'fr',
		6 => 'sa',
		7 => 'su'
	);

	/**
     * Construct an EventPage Object using an occurrence and a page
     *
     * @param DateTime $event DateTime Object representing a certain point in time
     * @param Page $page The page for which we're creating an event.
     *
     */
	function __construct(DateTime $event, $RRule, $page=NULL){
		//Set dateList to the list of occurrences on the original page
		$this->dateList = $page->occurrences;
		//Set reference to original page, this way we avoid calling all fields.
        $this->page = $page;
        
        //initialize date related properties using the occurrence instance.
        $this->initDateProps($event);
    }
    
    /**
     * initDateProps is a helper method to initialize the Date related props (dayName, dayNumber, etc.)
     *
     * @param DateTime $event DateTime Object representing a certain point in time
     *
     */
    private function initDateProps(DateTime $event) {
	    $this->dayName = self::week_days[$event->format('N')];
		$this->dayNumber = $event->format('j');
		$this->monthName = $event->format('F');
		$this->monthNumber = $event->format('n');
		$this->year = $event->format('Y');
    }
    
}