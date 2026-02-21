using System.Text.Json.Serialization;

public class Product {
    public int Id { get; set; }
    [JsonPropertyName("item")]
    public string Item { get; set; } = "";
    [JsonPropertyName("price")]
    public decimal Price { get; set; }
    [JsonPropertyName("quantity")]
    public int Quantity { get; set; }
}
