<?php
namespace PHPSC\Conference\Infra\Fixtures;

use PHPSC\Conference\Domain\Entity\SponsorshipQuota;

class SponsorshipQuotaFixture extends BaseFixture
{
    /**
     * {@inheritdoc}
     */
    public function import()
    {
        $this->createQuota(
            1,
            'Platina',
            2000,
            array(
                'Colocar o logo no site do evento, com link para o site da empresa',
                'Folder próprio inserido no material do evento',
                'Aparições no telão com agradecimento oficial',
                '5 cupons de 100% de desconto nas inscrições',
                'Palavra livre de 10 minutos para divulgar algum serviço e/ou produto'
            )
        );

        $this->createQuota(
            2,
            'Ouro',
            1000,
            array(
                'Colocar o logo no site do evento, com link para o site da empresa',
                'Folder próprio inserido no material do evento',
                '3 cupons de 100% de desconto nas inscrições',
                'Palavra livre de 5 minutos para divulgar algum serviço e/ou produto'
            )
        );

        $this->createQuota(
            3,
            'Prata',
            500,
            array(
                'Colocar o logo no site do evento, com link para o site da empresa',
                'Direito a expor um banner (máximo 80x120cm) na área comum do evento',
                '5 cupons de 20% de desconto nas inscrições'
            )
        );

        $this->createQuota(
            4,
            'Bronze',
            250,
            array(
                'Colocar o logo no site do evento, com link para o site da empresa',
                '3 cupons de 10% de desconto nas inscrições'
            )
        );

        $this->forceAssignedIds('PHPSC\Conference\Domain\Entity\SponsorshipQuota');
        $this->manager->flush();
    }

    /**
     * @param integer $id
     * @param string $title
     * @param float $cost
     * @param array $benefits
     */
    protected function createQuota($id, $title, $cost, array $benefits)
    {
        if ($this->findById($id)) {
            return ;
        }

        $quota = new SponsorshipQuota();
        $quota->setId($id);
        $quota->setTitle($title);
        $quota->setCost($cost);
        $quota->setBenefits($benefits);

        $this->manager->persist($quota);
    }

    /**
     * {@inheritdoc}
     */
    public function purge()
    {
        foreach (array(1, 2, 3, 4) as $id) {
            if ($type = $this->findById($id)) {
                $this->manager->remove($type);
            }
        }

        $this->manager->flush();
    }

    /**
     * @param int $id
     * @return SponsorshipQuota
     */
    protected function findById($id)
    {
        return $this->manager->getRepository('PHPSC\Conference\Domain\Entity\SponsorshipQuota')->find($id);
    }
}
