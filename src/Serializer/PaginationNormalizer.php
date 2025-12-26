<?php

declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

class PaginationNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private readonly NormalizerInterface $normalizer,
    ) {
    }

    public function normalize(mixed $data, ?string $format = null, array $context = []): array
    {

        if (!($data instanceof PaginationInterface)) {
            throw new \InvalidArgumentException('The data must be an instance of PaginationInterface');
        }

        // On récupère les items paginés
        $items = $data->getItems();

        // On normalise chaque item (par exemple chaque Recipe)
        $normalizedItems = array_map(function ($item) use ($format, $context) {
            return $this->normalizer->normalize($item, $format, $context);
        }, $items);

        // On crée notre tableau final
        $result = [
            'items' => $normalizedItems,
            'page' => $data->getCurrentPageNumber(),
            'perPage' => $data->getItemNumberPerPage(),
            'total' => $data->getTotalItemCount(),
        ];

        return $result;
    }

    public function supportsNormalization($data, ?string $format = null, array $context = []): bool
    {
        return $data instanceof PaginationInterface && $format === 'json';
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            PaginationInterface::class => true,
        ];
    }
}