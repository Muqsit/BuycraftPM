<?php

declare(strict_types=1);

namespace tebexio\pocketmine\api\user;

final class TebexPurchaseTotals{

	/** @var float[] */
	private $purchase_totals;

	/**
	 * @param float[] $purchase_totals
	 */
	public function __construct(array $purchase_totals){
		$this->purchase_totals = $purchase_totals;
	}

	/**
	 * @return float[]
	 */
	public function getAll() : array{
		return $this->purchase_totals;
	}

	public function get(string $currency) : ?float{
		return $this->purchase_totals[$currency] ?? null;
	}
}