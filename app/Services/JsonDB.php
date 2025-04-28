<?php

namespace App\Services;

use RuntimeException;
use Illuminate\Support\Collection;

/**
 * Servicio para gestionar archivos JSON como si fueran tablas de base de datos.
 */
class JsonDB
{
    protected static array $schemas = [];
    protected string $path;
    protected array $wheres = [];
    protected ?array $orderBy = null;
    protected array $structure = [];

    public static function schema(string $table, array $schema): void
    {
        self::$schemas[$table] = $schema;
    }

    public static function table(string $table, array $structure = []): self
    {
        $instance = new self();
        $instance->path = storage_path("config/jsons/{$table}.json");

        if (!empty($structure)) {
            self::$schemas[$table] = $structure;
        }

        $instance->structure = self::$schemas[$table] ?? [];

        if (!file_exists($instance->path)) {
            file_put_contents($instance->path, json_encode([], JSON_PRETTY_PRINT), LOCK_EX);
            chmod($instance->path, 0777);
        }

        return $instance;
    }

    public function get(): Collection
    {
        $items = $this->readJson();

        foreach ($this->wheres as $where) {
            [$field, $operator, $value] = $where;
            $items = array_filter($items, function ($item) use ($field, $operator, $value) {
                if (!isset($item[$field])) return false;
                switch ($operator) {
                    case '=': return $item[$field] == $value;
                    case '!=': return $item[$field] != $value;
                    case '>': return $item[$field] > $value;
                    case '<': return $item[$field] < $value;
                    case '>=': return $item[$field] >= $value;
                    case '<=': return $item[$field] <= $value;
                    default: return false;
                }
            });
        }

        if ($this->orderBy) {
            [$field, $direction] = $this->orderBy;
            usort($items, function ($a, $b) use ($field, $direction) {
                $aVal = $a[$field] ?? null;
                $bVal = $b[$field] ?? null;
                if ($aVal == $bVal) return 0;
                if ($direction === 'desc') {
                    return ($aVal < $bVal) ? 1 : -1;
                }
                return ($aVal > $bVal) ? 1 : -1;
            });
        }

        $this->reset();

        return collect(array_map(fn($item) => (object) $item, array_values($items)));
    }

    public function first(): ?object
    {
        return $this->get()->first();
    }

    public function insert(array $data): object
    {
        $items = $this->readJson();

        $maxId = 0;
        foreach ($items as $item) {
            $maxId = max($maxId, (int) $item['id']);
        }

        $data = $this->applyDefaults($data);
        $data['id'] = $maxId + 1;

        $items[] = $data;
        $this->writeJson($items);

        return (object) $data;
    }

    public function update(array $data): ?int
    {
        $items = $this->readJson();
        $updated = 0;

        foreach ($items as &$item) {
            if ($this->matchesWhere($item)) {
                $item = array_merge($item, $data);
                $updated++;
            }
        }

        if ($updated > 0) {
            $this->writeJson($items);
        }

        $this->reset();

        return $updated;
    }

    public function delete(): bool
    {
        $items = $this->readJson();
        $found = false;

        foreach ($items as &$item) {
            if ($this->matchesWhere($item)) {
                $item['eliminado'] = 1;
                $found = true;
            }
        }

        if ($found) {
            $this->writeJson($items);
        }

        $this->reset();

        return $found;
    }

    public function where(string $field, string $operator, mixed $value = null): self
    {
        if ($value === null) {
            $value = $operator;
            $operator = '=';
        }
        $this->wheres[] = [$field, $operator, $value];
        return $this;
    }

    public function orderBy(string $field, string $direction = 'asc'): self
    {
        $this->orderBy = [$field, strtolower($direction)];
        return $this;
    }

    protected function readJson(): array
    {
        $contents = file_get_contents($this->path);
        $data = json_decode($contents, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException('JSON inválido en: ' . $this->path);
        }

        return $data;
    }

    protected function writeJson(array $data): void
    {
        file_put_contents($this->path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
        chmod($this->path, 0777);
    }

    protected function matchesWhere(array $item): bool
    {
        foreach ($this->wheres as [$field, $operator, $value]) {
            if (!isset($item[$field])) return false;

            switch ($operator) {
                case '=': if ($item[$field] != $value) return false; break;
                case '!=': if ($item[$field] == $value) return false; break;
                case '>': if ($item[$field] <= $value) return false; break;
                case '<': if ($item[$field] >= $value) return false; break;
                case '>=': if ($item[$field] < $value) return false; break;
                case '<=': if ($item[$field] > $value) return false; break;
            }
        }
        return true;
    }

    protected function applyDefaults(array $data): array
    {
        foreach ($this->structure as $field => $typeInfo) {
            if (!array_key_exists($field, $data)) {
                if (str_contains($typeInfo, 'default:')) {
                    preg_match('/default:(.*)/', $typeInfo, $matches);
                    $default = trim($matches[1], '"');
                    $data[$field] = is_numeric($default) ? (int) $default : $default;
                } else {
                    $data[$field] = null;
                }
            }
        }
        return $data;
    }

    protected function reset(): void
    {
        $this->wheres = [];
        $this->orderBy = null;
    }
}