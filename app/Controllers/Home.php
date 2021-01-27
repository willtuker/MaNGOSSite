<?php

namespace App\Controllers;

use CodeIgniter\Database\Database;
use R;

class Home extends BaseController
{

	public function __construct()
	{
		if (!R::testConnection()) {
			R::setup("mysql:host=".RB_DB_HOST.";dbname=".RB_DB_DBNAME_WORLD, RB_DB_USER, RB_DB_PASW);
			R::addDatabase('realm', 'mysql:host='.RB_DB_HOST.';dbname='.RB_DB_DBNAME_REALM, RB_DB_USER, RB_DB_PASW, false);
			R::addDatabase('char0', 'mysql:host='.RB_DB_HOST.';dbname='.RB_DB_DBNAME_CHAR, RB_DB_USER, RB_DB_PASW, false);
		}

		R::ext('xdispense', function ($type) {
			return R::getRedBean()->dispense($type);
		});
	}

	public function index()
	{
		R::selectDatabase('realm');

		$realms = R::getAll('SELECT * FROM realmlist;');


		$realmObjects = array();

		foreach ($realms as $realm) {
			R::selectDatabase('char0');
			$onlinePlayer = R::getCol('SELECT count(*) FROM characters WHERE online = 1;')[0];
			$totalPlayer = R::getCol('SELECT count(*) FROM characters;')[0];

			array_push($realmObjects, json_decode('{ "id" : "' . $realm['id'] . '", 
					"name" : "' . $realm['name'] . '",
					"state" : "' . $realm['realmflags'] . '",
					"online" : "' . $onlinePlayer . '",
					"total" : "' . $totalPlayer . '" }', true));
		}

		$data['realms'] = $realmObjects;

		return view('index', $data);
	}

	public function feedbackOk()
	{
		R::selectDatabase('default');
		$qid = $this->request->getPost('id');

		$feedback = R::xdispense('quest_feedbacks');

		$feedback->quest_entry = $qid;
		$feedback->feedback = 1;

		$id = R::store($feedback);

		return "ok ";
	}


	public function feedbackKo()
	{
		$qid = $this->request->getPost('id');

		R::selectDatabase('default');
		$feedback = R::xdispense('quest_feedbacks');

		$feedback->quest_entry = $qid;
		$feedback->feedback = 0;

		$id = R::store($feedback);

		return "ok";
	}

	public function getPage()
	{
		R::selectDatabase('default');
		$offset = 0;
		$page = 1;
		$limit = 10;
		$searchText = "";
		if (!is_null($this->request->getGet('page'))) {
			$page = $this->request->getGet('page');

			$offset = ($page * $limit) - $limit;
		}
		if (!is_null($this->request->getGet('name'))) {
			$qName = $this->request->getGet('name');
			$searchText = $qName;
		}
		
		$query = 'SELECT qt.entry,
				qt.Title,
				sum(qf.feedback = 1) AS ok, 
				sum(qf.feedback = 0) AS ko	
			FROM quest_template AS qt
			LEFT JOIN quest_feedbacks AS qf
			ON qt.entry = qf.quest_entry
			WHERE qt.Title like \'%'.addslashes($searchText).'%\'
			GROUP BY qt.entry
			LIMIT '.$limit.' OFFSET '.$offset.';';
			// echo $query;
		$quests = R::getAll($query);
				

		$data['quests'] = $quests;
		$data['searchText'] = $searchText;

		$count = R::getCol('SELECT count(*) FROM quest_template WHERE Title like \'%' . $qName . '%\';')[0];
		$totalFeedbacks = R::getCol('SELECT count(*) FROM (SELECT distinct(quest_entry) FROM quest_feedbacks) as quests;')[0];
		$totalQuests = R::getCol('SELECT count(*) FROM quest_template;')[0];
		$rateFeedbacks = round(($totalFeedbacks / $totalQuests) * 100,2);

		R::selectDatabase('char0');
		$totalDone = R::getCol('SELECT count(*) FROM (SELECT distinct(quest) FROM character_queststatus WHERE status = 1) as done;')[0];
		$totaleProgress = R::getCol('SELECT count(*) FROM (SELECT distinct(quest) FROM character_queststatus WHERE status = 3) as done;')[0];
		$totaleForced = R::getCol('SELECT count(*) FROM (SELECT distinct(quest) FROM character_queststatus WHERE status = 6) as done;')[0];
		$data['totalDone'] = $totalDone;
		$data['totaleProgress'] = $totaleProgress;
		$data['totaleForced'] = $totaleForced;
		$data['rateDones'] = round(($totalDone / $totalQuests) * 100,2);

		$minpage = 1;
		$maxpage = 8;
		$nextpage = $page + 1;
		$prevpage = $page - 1;
		$totalpages = ceil($count / $limit);
		if ($page > 3) {
			$minpage = $page - 3;
			$maxpage = $page + 3;
		}
		if ($maxpage > $totalpages) {
			$maxpage = $totalpages;
		}
		if ($prevpage < 1) {
			$prevpage = 1;
		}
		if ($nextpage > $totalpages) {
			$nextpage = $totalpages;
		}
		$data['minpage'] = $minpage;
		$data['maxpage'] = $maxpage;
		$data['prevpage'] = $prevpage;
		$data['nextpage'] = $nextpage;
		$data['totalpages'] = $totalpages;
		$data['page'] = $page;
		$data['totalFeedbacks'] = $totalFeedbacks;
		$data['totalQuests'] = $totalQuests;
		$data['rateFeedbacks'] = $rateFeedbacks;

		return view('quest_table', $data);
	}

	public function quest()
	{
		if (!is_null($this->request->getGet('id'))) {
			$qId = $this->request->getGet('id');

			$quest = R::getAll('SELECT entry, MinLevel, QuestLevel, SuggestedPlayers,PrevQuestId, 
										NextQuestId, NextQuestInCHain, Title, Details, Objectives,
										RewChoiceItemId1, RewChoiceItemId2, RewChoiceItemId3, 
										RewChoiceItemId4, RewChoiceItemId5, RewChoiceItemId6, 
										RewOrReqMoney, RewMoneyMaxLevel
				FROM quest_template 
				WHERE entry = \'' . $qId . '\';')[0];

			R::selectDatabase('char0');
			$timesDone = R::getCol('SELECT count(*) FROM character_queststatus WHERE status = 1 AND quest = '. $qId .';')[0];
			
			$rewardsIds = array();

			if($quest['RewChoiceItemId1'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId1']);
			}
			if($quest['RewChoiceItemId2'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId2']);	
			}
			if($quest['RewChoiceItemId3'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId3']);
			}
			if($quest['RewChoiceItemId4'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId4']);
			}
			if($quest['RewChoiceItemId5'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId5']);
			}
			if($quest['RewChoiceItemId6'] != 0)
			{
				array_push($rewardsIds, $quest['RewChoiceItemId6']);
			}

			if(count($rewardsIds) > 0)
			{

				$comma_separated = implode(",", $rewardsIds);
				
				R::selectDatabase('default');
				$queryRewards = 'SELECT * FROM item_template WHERE entry IN ('.$comma_separated.');';

				$rewards = R::getAll($queryRewards);
				$data['rewards'] = $rewards;
			}

			$data['quest'] = $quest;
			$data['RewardExperience'] = $quest['RewMoneyMaxLevel'];
			$data['RewardMoney'] = intval($quest['RewOrReqMoney']);
			$data['RewardMoneyGold'] = $this->convertToGold(intval($quest['RewOrReqMoney']));
			$data['RewardMoneySilver'] = $this->convertToSilver(intval($quest['RewOrReqMoney']));
			$data['RewardMoneyCopper'] = $this->convertToCopper(intval($quest['RewOrReqMoney']));
			$data['timesDone'] = $timesDone;

			return view('quest_detail', $data);
		}
	}

	public function convertToGold($money)
	{
		$returnVal = 0;
		$baseMoney = $money;
		$gold = 0;
		$silver = 0;
		$copper = 0;

		if($baseMoney > 10000)
		{
			$gold =	intval($baseMoney / 10000);
			$baseMoney -= $gold * 10000;
		}

		if($gold > 0)
		{
			$returnVal = $gold;
		}

		return $returnVal;
	}

	public function convertToSilver($money)
	{
		$returnVal = 0;
		$baseMoney = $money;
		$gold = 0;
		$silver = 0;
		$copper = 0;

		if($baseMoney > 10000)
		{
			$gold =	intval($baseMoney / 10000);
			$baseMoney -= $gold * 10000;
		}
		if($baseMoney > 100)
		{
			$silver = intval($baseMoney / 100);
			$baseMoney -= $silver * 100;
		}

		if($silver > 0)
		{
			$returnVal = $silver;
		}

		return $returnVal;
	}

	public function convertToCopper($money)
	{
		$returnVal = 0;
		$baseMoney = $money;
		$gold = 0;
		$silver = 0;
		$copper = 0;

		if($baseMoney > 10000)
		{
			$gold =	intval($baseMoney / 10000);
			$baseMoney -= $gold * 10000;
		}
		if($baseMoney > 100)
		{
			$silver = intval($baseMoney / 100);
			$baseMoney -= $silver * 100;
		}
		if($baseMoney > 0)
		{
			$copper = $baseMoney;
		}
	
		if($copper > 0)
		{
			$returnVal = $copper;
		}
		
		return $returnVal;
	}
	//--------------------------------------------------------------------

}
