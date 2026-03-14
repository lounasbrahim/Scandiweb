import React from 'react';
import { useQuery } from '@apollo/client';
import { Link } from 'react-router-dom';
import { GET_CATEGORIES, type GetCategoriesData } from '../graphQl/queries';
import type { CartItem } from '../types';
import logo from '../Images/a-logo.png';

interface HeaderProps {
  onCategoryChange: (name: string) => void;
  onCartToggle: () => void;
  cartItems: CartItem[];
  category: string;
}

export default function Header({ onCategoryChange, cartItems, onCartToggle, category }: HeaderProps) {
  const { loading, error, data } = useQuery<GetCategoriesData>(GET_CATEGORIES);

  if (loading) return <p>Loading...</p>;
  if (error) return <p>Error Loading Categories</p>;

  const totalQuantity = cartItems.reduce((acc, item) => acc + item.quantity, 0);

  return (
    <header className="sticky top-0 z-40 bg-white shadow-sm flex items-center justify-between px-2 sm:px-4">
      <nav className="flex overflow-x-auto">
        {data?.categories?.map(({ name }) => (
          <Link
            to={`/${name.toLowerCase()}`}
            data-testid={category === name ? 'active-category-link' : 'category-link'}
            key={name}
            onClick={() => onCategoryChange(name)}
            className={`flex-shrink-0 uppercase text-xs sm:text-sm px-2 sm:px-4 pt-3 sm:pt-4 pb-4 sm:pb-6 text-gray-950 whitespace-nowrap ${
              category === name
                ? 'text-green-600'
                : 'bg-white hover:bg-green-100'
            }`}
          >
            {name}
          </Link>
        ))}
      </nav>

      <div className="flex-shrink-0">
        <Link to="/">
          <img src={logo} alt="logo" className="h-8 sm:h-10 w-auto" />
        </Link>
      </div>

      <button onClick={onCartToggle} data-testid="cart-btn" className="relative flex-shrink-0 p-2">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          fill="none"
          viewBox="0 0 22 22"
          strokeWidth="1"
          stroke="currentColor"
          className="h-5 w-5 sm:h-6 sm:w-6 text-gray-950"
        >
          <path
            strokeLinecap="round"
            strokeLinejoin="round"
            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
          />
        </svg>
        {totalQuantity > 0 && (
          <span className="absolute -top-0 -right-1 bg-gray-950 text-white text-xs font-semibold rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center">
            {totalQuantity}
          </span>
        )}
      </button>
    </header>
  );
}
