import React from 'react';
import { Link } from 'react-router-dom';
import type { Product, CartItem } from '../types';

interface ProductCardProps {
  product: Product;
  addTocart: (item: CartItem) => void;
  openCart: () => void;
}

export default function ProductCard({ product, addTocart, openCart }: ProductCardProps) {
  const { id, name, gallery, in_stock, price, attributes } = product;

  const firstPic = gallery?.[0];

  const handleAddToCart = (): void => {
    const defaultSelectedAttributes: Record<string, string> = {};

    attributes.forEach((attr) => {
      if (attr.items.length > 0) {
        defaultSelectedAttributes[attr.name] = attr.items[0].value;
      }
    });

    const cartItem: CartItem = {
      name,
      quantity: 1,
      price,
      image: gallery?.[0] ?? '',
      selectedAttributes: defaultSelectedAttributes,
      attributes,
    };

    addTocart(cartItem);
    openCart();
  };

  return (
    <div
      className="group relative bg-white shadow-sm rounded-md overflow-hidden hover:shadow-md"
      data-testid={`product-${name.toLowerCase().replace(/[^a-z0-9]+/g, '-')}`}
    >
      <Link to={`/product/${id}`}>
        <div className="relative bg-white aspect-[2/2] bg-gray-50 p-3">
          <img
            src={firstPic}
            alt={name}
            className={`w-full h-full rounded-md object-cover transition duration-300 ${!in_stock ? 'opacity-30' : ''}`}
          />
          {!in_stock && (
            <div className="absolute inset-0 flex items-center justify-center">
              <span className="text-xl text-gray-700 font-semibold">OUT OF STOCK</span>
            </div>
          )}
        </div>

        <div className="p-2">
          <h3 className="text-sm text-gray-700">{name}</h3>
          <p className="text-base font-medium">${price.toFixed(2)}</p>
        </div>
      </Link>

      {in_stock && (
        <div
          className="absolute bottom-14 sm:bottom-16 right-3 sm:right-4 bg-[#5ece7b] p-1.5 sm:p-2 rounded-full opacity-0 group-hover:opacity-100 transition cursor-pointer z-10"
          onClick={(e) => {
            e.preventDefault();
            handleAddToCart();
          }}
        >
          <svg
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            strokeWidth="1.5"
            stroke="currentColor"
            className="h-6 w-6 text-white"
          >
            <path
              strokeLinecap="round"
              strokeLinejoin="round"
              d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
            />
          </svg>
        </div>
      )}
    </div>
  );
}
