<?php
	
/** 
* Requiring these in case the Occurrence Page wants to be used without the module wrapper 
* Because I know that there's one of you that's going to try that
*/
require_once(dirname(__FILE__) . '/php-rrule/src/RRuleInterface.php');
require_once(dirname(__FILE__) . '/php-rrule/src/RRule.php');
require_once(dirname(__FILE__) . '/php-rrule/src/RSet.php');

/**
 * OccurrencePage 
 *
 * Helper class for handling RRule Occurrences
 * Stores properties for easier access and holds a reference to the original page.
 *
 * @method private void initDateProps(DateTime $rrule)
 * @method private void initRRuleProps(RRule $rrule)
 *
 */

class OccurrencePage{
	
	/**
     * Reference to the original page;
     *
     * @var Page
     *
     */
	public $page = NULL;
	
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
     * Number of times the occurrence has repeated, including this one
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
     * @var Array
     *
     */
	public $dateList = NULL;
	
	/**
     * The RRule Object from which the OccurrencePage was made
     *
     * @var RRule
     *
     */
	public $rrule = null;
	
	/**
     * The RRule String from which the OccurrencePage was made
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
     * Construct an OccurrencePage Object using an occurrence and a page
     *
     * @param DateTime $occurrence DateTime Object representing a certain point in time.
     * @param RRule $rrule The RRule used to generate the occurrences.
     * @param Page $page The page for which we're creating an occurrence.
     * @param int $repeatCount number of times the reccurrence has repeated
     *
     */
	function __construct(DateTime $occurrence, RRule\RRule $rrule, Page $page=NULL, int $repeatCount = 0){
		//Set reference to original page, this way we avoid calling all fields.
        $this->page = $page;
        
        //Set repeatCount as the number of occurrence that this represents
        $this->repeatCount = $repeatCount;
        
        //Set isOriginal based on the number of occurrence repeat
        $this->isOriginal = ($repeatCount > 0) ? false : true;
        
        //Initialize RRule related properties using the
        $this->initRRuleProps($rrule);
        
        //initialize date related properties using the occurrence instance.
        $this->initDateProps($occurrence);
    }
    
    /**
     * initDateProps is a helper method to initialize the Date related props (dayName, dayNumber, etc.)
     *
     * @param DateTime $occurrence DateTime Object representing a certain point in time
     * @param RRule $rrule RRule Object representing the original reccurrence rule from which the occurrence came
     */
    private function initDateProps(DateTime $occurrence) {
	    $this->dayName = self::week_days[$occurrence->format('N')];
		$this->dayNumber = $occurrence->format('j');
		$this->monthName = $occurrence->format('F');
		$this->monthNumber = $occurrence->format('n');
		$this->year = $occurrence->format('Y');
		$this->date = $occurrence;
    }
    
    /**
     * initRRuleProps is a helper method to initialize the RRule related props (rrule, rruleString, etc.)
     *
     * @param RRule $rrule
     *
     */
    private function initRRuleProps(RRule\RRule $rrule) {
	    //Getting the internal RRule Array;
	    $rule = $rrule->getRule();
	    
	    //Getting the recurrence count
	    $count = count($rrule);
	    
	    //Setting the RRule related properties
		$this->dateStart = $rule['DTSTART'];
		$this->dateUntil = ($rule['UNTIL']) ? $rule['UNTIL'] : $rrule[$count - 1] ;
		$this->frequency = $rule['FREQ'];
		$this->interval = $rule['INTERVAL'];
		$this->count = $count;
		$this->dateList = $rrule->getOccurrences();
		$this->rrule = $rrule;
		$this->rruleToText = $rrule->rfcString();
    }
    
}