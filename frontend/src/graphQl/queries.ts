import { gql } from '@apollo/client';
import type { Category, Product } from '../types';

export interface GetCategoriesData {
  categories: Category[];
}

export interface GetProductsByCategoryData {
  categoryProducts: Product[];
}

export interface GetProductByIdData {
  product: Product | null;
}

export interface PlaceOrderData {
  placeOrder: {
    id: string;
    total_price: number;
    created_at: string;
  };
}

export const GET_CATEGORIES = gql`
  query GetCategories {
    categories {
      name
    }
  }
`;

export const GET_PRODUCTS_BY_CATEGORY = gql`
  query GetProductsByCategory($category: String!) {
    categoryProducts(category: $category) {
      id
      name
      brand
      in_stock
      price
      gallery
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
        }
      }
    }
  }
`;

export const GET_PRODUCT_BY_ID = gql`
  query GetProduct($id: ID!) {
    product(id: $id) {
      id
      name
      brand
      description
      in_stock
      category
      gallery
      price
      attributes {
        id
        name
        type
        items {
          id
          displayValue
          value
        }
      }
    }
  }
`;

export const PLACE_ORDER = gql`
  mutation PlaceOrder($items: [OrderItemInput!]!, $totalPrice: Float!) {
    placeOrder(items: $items, totalPrice: $totalPrice) {
      id
      total_price
      created_at
    }
  }
`;
