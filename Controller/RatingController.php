<?php

namespace Meisa\RatingBundle\Controller;

use Meisa\RatingBundle\Entity\Rating;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Response;

class RatingController extends Controller
{
    /**
     * @Route("/test_rating")
     * @Template()
     */
    public function indexAction()
    {
        $id = $this->container->get('request')->get('id');
        $id = 'rating_1';
        $ratingObject = $this->getDoctrine()->getManager()->getRepository(get_class(new Rating()))->findOneBy(array('voteId' => $id));
        $totalVotes = 0;
        $rating = 0;
        if ($ratingObject) {
            $totalVotes = $ratingObject->getTotalVotes();
            $totalValue = $ratingObject->getTotalValue();
            $rating = ($rating != 0) ? $totalValue / $totalVotes : 0;
        }
        return array('rating' => $rating, 'total_votes' => $totalVotes, 'id' => $id);
    }

    /**
     * @Route("/meisa_vote", name ="meisa_vote", options={"expose"=true})
     */
    public function voteAction()
    {
        $translator = $this->get('translator');
        $units = 5;
        $em = $this->getDoctrine()->getManager();
        $request = $this->container->get('request');
        $id = $request->get('id');
        $vote_sent = preg_replace("/[^0-9]/", "", $request->get('stars'));
        $ip = $_SERVER['REMOTE_ADDR'];

        $rating = $em->getRepository(get_class(new Rating()))->findOneBy(array('voteId' => $id));
        if (!$rating) {
            $rating = new Rating();
            $rating->setVoteId($id);
            $rating->setDate(new \DateTime("now"));
            $em->persist($rating);
            $em->flush();
        }
        if ($vote_sent > $units) die("Sorry, vote appears to be invalid."); // kill the script because normal users will never see this.

//connecting to the database to get some informationvar_dump(unserialize($data));

        $checkIP = unserialize($rating->getusedIps());
        $count = $rating->getTotalVotes(); //how many votes total
        $current_rating = $rating->getTotalValue(); //total number of rating added together and stored
        $sum = $vote_sent + $current_rating; // add together the current vote value and the total vote value
        $tense = ($count == 1) ?  $translator->trans('vote') :  $translator->trans('votes'); //plural form votes/vote

// checking to see if the first vote has been tallied
// or increment the current number of votes
        ($sum == 0 ? $added = 0 : $added = $count + 1);

// if it is an array i.e. already has entries the push in another value
        ((is_array($checkIP)) ? array_push($checkIP, $ip) : $checkIP = array($ip));
        $insertedIp = serialize($checkIP);

//IP check when voting
        $query = "SELECT p FROM MeisaRatingBundle:Rating p where p.usedIps like '%" . $ip . "%' and p.voteId =:id";
        $query = $em->createQuery($query)->setParameter('id', $id);
        $voted = $query->getResult();

        if (!$voted) {     //if the user hasn't yet voted, then vote normally...
            if (($vote_sent >= 1 && $vote_sent <= $units)) { // keep votes within range, make sure IP matches
                $oldRating = $em->getRepository(get_class(new Rating()))->findOneBy(array('voteId' => $id));
                $oldRating->setTotalVotes($added);
                $oldRating->setTotalValue($sum);
                $oldRating->setUsedIps("" . $insertedIp . "");
                $qb = $em->createQueryBuilder();
                $em->persist($oldRating);
                $em->flush($oldRating);
                if ($oldRating) setcookie("rating_" . $id, 1, time() + 2592000);
                $count++;
                $current_rating = $oldRating->getTotalValue();
            }
        } //end for the "if(!$voted)"
        $tense = ($added == 1) ?  $translator->trans('vote') :  $translator->trans('votes'); //plural form votes/vote

// $new_back is what gets 'drawn' on your page after a successful 'AJAX/Javascript' vote
        if ($voted) {
            $sum = $current_rating;
            $added = $count;
        }
        $new_back = array();
        for ($i = 0; $i < 5; $i++) {
            $j = $i + 1;
            if ($i < @number_format($current_rating / $count, 1) - 0.5) $class = "ratings_stars glyphicon-star";
            else $class = "glyphicon-star-empty";
            $new_back[] .= '<span class="glyphicon ratings_stars permanent-star star_' . $j . ' ' . $class . '"></span>';
        }

        $new_back[] .= '<div class="total_votes"><p class="voted"> ';
        if (!$voted) $new_back[] .= '<span class="thanks">'.$translator->trans('Thanks').'</span>';
        else {
            $new_back[] .= '<span class="invalid">'.$translator->trans('already voted').'</span>';
        }
        $votesNumbersCount = '';
        if ($count == 0) {
            $new_back[] .= '0 ' . $translator->trans('votes') . '</p></div>';
        } elseif ($count == 1) {
            $new_back[] .= $count . ' ' . $translator->trans('vote') . '</p></div>';
        } elseif ($count == 2) {
            $new_back[] .= $translator->trans('2 votes') . '</p></div>';
        } else {
            $new_back[] .= $count . ' ' . $translator->trans('votes') . '</p></div>';
        }
        $allnewback = join("\n", $new_back);

// ========================
        $output = $allnewback;

        return new Response($output);
    }

}
