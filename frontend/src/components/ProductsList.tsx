import React from 'react';
import { useQuery } from '@apollo/client';
import { GET_PRODUCTS_BY_CATEGORY, type GetProductsByCategoryData } from '../graphQl/queries';
import type { CartItem } from '../types';
import ProductCard from './ProductCard';

interface ProductListProps {
  category: string;
  addTocart: (item: CartItem) => void;
  openCart: () => void;
}

const ProductList = ({ category, addTocart, openCart }: ProductListProps) => {
  const { loading, error, data } = useQuery<GetProductsByCategoryData>(GET_PRODUCTS_BY_CATEGORY, {
    variables: { category },
    skip: !category,
  });

  if (loading) return <p className="p-4">Loading products...</p>;
  if (error) return <p className="p-4 text-red-500">Error loading products</p>;

  const products = data?.categoryProducts ?? [];

  if (!products.length) {
    return <p className="p-4">No products found for this category.</p>;
  }

  return (
    <div className="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-12 p-4">
      {products.map((product) => (
        <ProductCard key={product.id} product={product} addTocart={addTocart} openCart={openCart} />
      ))}
    </div>
  );
};

export default ProductList;
