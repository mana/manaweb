<?php
/*
 *  The Mana World Account Manager
 *  Copyright 2008 The Mana World Development Team
 *
 *  This file is part of The Mana World.
 *
 *  The Mana World  is free software; you can redistribute  it and/or modify it
 *  under the terms of the GNU General  Public License as published by the Free
 *  Software Foundation; either version 2 of the License, or any later version.
 *
 *  The Mana  World is  distributed in  the hope  that it  will be  useful, but
 *  WITHOUT ANY WARRANTY; without even  the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should  have received a  copy of the  GNU General Public  License along
 *  with The Mana  World; if not, write to the  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 */
 
 
/**
 * The auction model represents an auction where player can offer items to other
 * players.
 *
 * @ingroup models
 */ 
class Auction 
{
	private $CI;			/**< Reference to the CodeIgniter framework. */
    private $data;			/**< Database record representing the auction */
    private $iteminfo;		/**< Itemclass object that is sold. */
    private $bidstats;		/**< Statistics about this auction. */
    
    
    const AUCTION_BID_COUNT      = 0;	/**< Number of bids made to this auction. */
    const AUCTION_MAX_PRICE      = 1;	/**< Maximum price bidden by another user. */
    const AUCTION_LAST_BID_TIME  = 2;	/**< Last time a user made a bid to this auction. */
    
    
    /**
     * Returns an array of the next 10 auctions that will finish.
     * @return Array with next 10 auctions to finish.
     */
    public static function getFinishingAuctions()
    {
	    /*
	    SELECT *
		FROM   mana_auctions
		WHERE  end_time > "now"
		AND    auction_state = 1
		ORDER  BY end_time DESC;
		*/
		
	    $auctions = array();
	    $db =& get_instance()->db;
	    
	    $query = $db->get('mana_auctions');
	    foreach ($query->result() as $row)
	    {
		    $auctions[] = new Auction($row);
	    }
	    
	    return $auctions;
    }
    
    
    /**
     * Creates a new map object. Id and name are required.
     * 
     * @param data   (array)    Database record representing the auction
     */
    public function __construct($data)
    {
        // get an instance of CI
        // we have to do this, because we are not in an controller and 
        // therefore we cannot access $this->config directly
        $this->CI =& get_instance();
        
        $this->data = $data;
        $this->iteminfo = null;
        $this->bidstats = null;
    }
    
    
    /**
     * Gets the unique id of the auction.
     * @return Unique id of the auction.
     */
    public function getId()
    {
	    return $this->data->auction_id;
    }
    
    
    /**
     * Gets the itemclass object, that is sold in this auction.
     */
    public function getItem()
    {
	    if (!isset($this->iteminfo))
	    {
		    $db =& $this->CI->db;
		    $query = $db->get_where('mana_items', 
		    	array('id'=>$this->data->itemclass_id));
		    	
		    $row = $query->result();
		    $this->iteminfo = $row[0];
	    }
	    return $this->iteminfo;
    }
    
    
    /**
     * Gets the character that owns this auction.
     * @return Character
     */
    public function getSeller()
    {
	    return $this->CI->user->getCharacter( $this->data->char_id );
    }
    
    
    /**
     * Gets the ending time of the auction.
     * @return Unixtimestamp of the auction ending.
     */
    public function getEndtime()
    {
	    return $this->data->end_time;
    }
    
    
    /**
     * Gets the time until this auction finishs.
     * @return array with days, hours, minutes and seconds.
     */
    public function getDuration()
    {
	    return $this->getTimeDiff( time(), $this->data->end_time );
    }
    
    
    /**
     * Returns the number of bids and the maximum price offered by this auction.
     * @return Array with number of bids[0] and maximum price offered.
     */     
    public function getAuctionStats($statvalue)
    {
	    if (!$this->bidstats)
	    {
		    $db =& $this->CI->db;
		    
		    $sql = "SELECT MAX(bid_price) AS max_price, ".
		           "       MAX(bid_time) AS last_bid_time, ".
		           "       COUNT(*) AS bid_count ".
		           "FROM   mana_auction_bids ".
		           "WHERE  auction_id = " . $this->data->auction_id;
		    
		    $query = $db->query($sql);
		    
		    $row = $query->result();
		    $row = $row[0];
		    
		    $this->bidstats = array(
		    	Auction::AUCTION_BID_COUNT 		=> $row->bid_count,
		    	Auction::AUCTION_MAX_PRICE 		=> $row->max_price,
		    	Auction::AUCTION_LAST_BID_TIME 	=> $row->last_bid_time
		    	);
	    }
	    
	    if (!isset($this->bidstats[$statvalue]))
	    {
		    return "n/a";
	    }
	    
	    return $this->bidstats[$statvalue];
    }
    
    
    /**
	 * Function to calculate date and time differences.
	 * @param start Starttime
	 * @param end Endtime
	 * @return array containing d, h, m, s values
	 */
	function getTimeDiff($start, $end)
	{
        if( $start < $end )
        {
            $diff = $end - $start;
            // compute days
            $days = intval((floor($diff/86400)));
            if($days)
				$diff = $diff % 86400;
			// compute hours
            $hours=intval((floor($diff/3600)));
            if($hours)
				$diff = $diff % 3600;
			// compute minutes
            $minutes=intval((floor($diff/60)));
            if($minutes)
				$diff = $diff % 60;
			// the rest in seconds
            $diff = intval( $diff );
            
            return( array('d'=>$days, 'h'=>$hours, 'm'=>$minutes, 's'=>$diff) );
        }
        return false;
	}

} // class Auction
?>
