<?php
namespace Lcobucci\Common\Paginate;

use \OutOfRangeException;
use \InvalidArgumentException;

/**
 * @author Luís Otávio Cobucci Oblonczyk <lcobucci@gmail.com>
 */
class PaginationInformation
{
    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    /**
     * @var int
     */
    private $currentPage;

    /**
     * @var int
     */
    private $numberOfPages;

    /**
     * @var array
     */
    private $dataset;

    /**
     * @param int $total
     * @param int $limit
     * @param int $currentPage
     */
    public function __construct($total, $limit, $currentPage)
    {
        $this->setTotal($total);
        $this->setLimit($limit);
        $this->setCurrentPage($currentPage);
        $this->setNumberOfPages(ceil($total / $limit));
        $this->setOffset($limit * ($currentPage - 1));

        if ($currentPage > $this->getNumberOfPages()) {
            throw new OutOfRangeException('Invalid page');
        }
    }

	/**
     * @return number
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }

	/**
     * @param number $currentPage
     */
    protected function setCurrentPage($currentPage)
    {
        if ($currentPage <= 0) {
            throw new InvalidArgumentException('Current page must be greater than zero');
        }

        $this->currentPage = $currentPage;
    }

	/**
     * @return number
     */
    public function getTotal()
    {
        return $this->total;
    }

	/**
     * @param number $total
     */
    protected function setTotal($total)
    {
        $this->total = $total;
    }

	/**
     * @return number
     */
    public function getLimit()
    {
        return $this->limit;
    }

	/**
     * @param number $limit
     */
    protected function setLimit($limit)
    {
        if ($limit <= 0) {
            throw new InvalidArgumentException('Limit must be greater than zero');
        }

        $this->limit = $limit;
    }

	/**
     * @return number
     */
    public function getOffset()
    {
        return $this->offset;
    }

	/**
     * @param number $offset
     */
    protected function setOffset($offset)
    {
        $this->offset = $offset;
    }
	/**
     * @return number
     */
    public function getNumberOfPages()
    {
        return $this->numberOfPages;
    }

	/**
     * @param number $numberOfPages
     */
    protected function setNumberOfPages($numberOfPages)
    {
        $this->numberOfPages = $numberOfPages;
    }

	/**
     * @return array
     */
    public function getDataset()
    {
        return $this->dataset;
    }

	/**
     * @param array $dataset
     */
    public function setDataset(array $dataset)
    {
        $this->dataset = $dataset;
    }
}