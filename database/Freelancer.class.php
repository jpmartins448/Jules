<?php
require_once('User.class.php');

class Freelancer extends User {
    protected $skills;
    protected $hourlyRate;
    protected $portfolio;

    public function __construct($id, $username, $email, $name, $skills = [], $hourlyRate = 0, $portfolio = []) {
        parent::__construct($id, $username, $email, $name, 'freelancer');
        $this->skills = $skills;
        $this->hourlyRate = $hourlyRate;
        $this->portfolio = $portfolio;
    }

    public function getSkills() {
        return $this->skills;
    }

    public function getHourlyRate() {
        return $this->hourlyRate;
    }

    public function getPortfolio() {
        return $this->portfolio;
    }

    public function addSkill($skill) {
        $this->skills[] = $skill;
    }

    public function addPortfolioItem($item) {
        $this->portfolio[] = $item;
    }
}
