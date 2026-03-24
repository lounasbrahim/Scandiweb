import React, { useState } from 'react';
import { Routes, Route, Navigate, useNavigate } from 'react-router-dom';
import Header from './Header';
import ProductList from './ProductsList';
import CartOverlay from './CartOverlay';
import SingleProduct from './SingleProduct';
import useCart from '../hooks/useCart';

export default function AppContent() {
  const navigate = useNavigate();
  const pathCategory = window.location.pathname.split('/')[1] || 'all';
  const [selectedCategory, setSelectedCategory] = useState<string>(pathCategory);
  const [cartOpen, setCartOpen] = useState<boolean>(false);

  const {
    cartItems,
    addToCart,
    increaseQuantity,
    decreaseQuantity,
    clearCart,
  } = useCart();

  const handleOrderPlaced = (): void => {
    clearCart();
    setCartOpen(false);
    setSelectedCategory('all');
    navigate('/all');
  };

  return (
    <>
      <Header
        onCategoryChange={setSelectedCategory}
        onCartToggle={() => setCartOpen((prev) => !prev)}
        cartItems={cartItems}
        category={selectedCategory}
      />

      <CartOverlay
        isOpen={cartOpen}
        onClose={() => setCartOpen(false)}
        cartItems={cartItems}
        decreaseQuantity={decreaseQuantity}
        increaseQuantity={increaseQuantity}
        clearCart={handleOrderPlaced}
      />

      <main className="p-4 sm:p-6">
        <Routes>
          <Route path="/" element={<Navigate to="/all" replace />} />
          <Route
            path="/:category"
            element={
              <>
                <h3 className="text-2xl sm:text-3xl text-gray-950 uppercase mb-8 sm:mb-20">{selectedCategory}</h3>
                <ProductList category={selectedCategory} addTocart={addToCart} openCart={() => setCartOpen(true)} />
              </>
            }
          />
          <Route
            path="/product/:id"
            element={<SingleProduct addTocart={addToCart} openCart={() => setCartOpen(true)} />}
          />
        </Routes>
      </main>
    </>
  );
}
