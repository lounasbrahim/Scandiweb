export interface AttributeItem {
  id: string;
  displayValue: string;
  value: string;
}

export interface Attribute {
  id: string;
  name: string;
  type: string;
  items: AttributeItem[];
}

export interface Product {
  id: string;
  name: string;
  brand: string;
  description: string;
  in_stock: boolean;
  category: string;
  gallery: string[];
  price: number;
  attributes: Attribute[];
}

export interface CartItem {
  name: string;
  price: number;
  image: string;
  selectedAttributes: Record<string, string>;
  attributes: Attribute[];
  quantity: number;
}

export interface Category {
  name: string;
}
