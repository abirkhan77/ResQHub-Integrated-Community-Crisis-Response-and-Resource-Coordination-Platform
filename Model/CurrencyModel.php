
<?php

require_once __DIR__ . '/Database.php';

class CurrencyModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getAllCurrencies()
    {
        $sql = "SELECT currency_code, currency_name, symbol
                FROM currency
                ORDER BY currency_code ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getCurrencyByCode($currencyCode)
    {
        $sql = "SELECT currency_code, currency_name, symbol
                FROM currency
                WHERE currency_code = :currency_code
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':currency_code' => $currencyCode]);

        return $stmt->fetch();
    }

    public function isCurrencySupported($currencyCode)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM currency
                WHERE currency_code = :currency_code";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':currency_code' => $currencyCode]);

        $result = $stmt->fetch();

        return $result['total'] > 0;
    }

    public function addCurrency($currencyCode, $currencyName, $symbol)
    {
        $sql = "INSERT INTO currency
                (currency_code, currency_name, symbol)
                VALUES
                (:currency_code, :currency_name, :symbol)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':currency_code' => $currencyCode,
            ':currency_name' => $currencyName,
            ':symbol'        => $symbol
        ]);
    }

    public function removeCurrency($currencyCode)
    {
        $sql = "DELETE FROM currency
                WHERE currency_code = :currency_code";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':currency_code' => $currencyCode]);
    }
}

<?php

require_once __DIR__ . '/Database.php';

class CurrencyModel
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::connect();
    }

    public function getAllCurrencies()
    {
        $sql = "SELECT currency_code, currency_name, symbol
                FROM currency
                ORDER BY currency_code ASC";

        $stmt = $this->conn->query($sql);

        return $stmt->fetchAll();
    }

    public function getCurrencyByCode($currencyCode)
    {
        $sql = "SELECT currency_code, currency_name, symbol
                FROM currency
                WHERE currency_code = :currency_code
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':currency_code' => $currencyCode]);

        return $stmt->fetch();
    }

    public function isCurrencySupported($currencyCode)
    {
        $sql = "SELECT COUNT(*) AS total
                FROM currency
                WHERE currency_code = :currency_code";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':currency_code' => $currencyCode]);

        $result = $stmt->fetch();

        return $result['total'] > 0;
    }

    public function addCurrency($currencyCode, $currencyName, $symbol)
    {
        $sql = "INSERT INTO currency
                (currency_code, currency_name, symbol)
                VALUES
                (:currency_code, :currency_name, :symbol)";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([
            ':currency_code' => $currencyCode,
            ':currency_name' => $currencyName,
            ':symbol'        => $symbol
        ]);
    }

    public function removeCurrency($currencyCode)
    {
        $sql = "DELETE FROM currency
                WHERE currency_code = :currency_code";

        $stmt = $this->conn->prepare($sql);

        return $stmt->execute([':currency_code' => $currencyCode]);
    }
}
