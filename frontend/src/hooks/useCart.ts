import { useState, useEffect } from 'react';
import type { CartItem } from '../types';

const useCart = () => {
  const [cartItems, setCartItems] = useState<CartItem[]>(() => {
    const storedCart = localStorage.getItem('cartItems');
    return storedCart ? (JSON.parse(storedCart) as CartItem[]) : [];
  });

  useEffect(() => {
    localStorage.setItem('cartItems', JSON.stringify(cartItems));
  }, [cartItems]);

  const addToCart = (newItem: CartItem): void => {
    setCartItems((prevItems) => {
      const existingIndex = prevItems.findIndex((item) => {
        if (item.name !== newItem.name) return false;

        const existingAttributes = item.selectedAttributes;
        const newAttributes = newItem.selectedAttributes;
        const allKeys = Object.keys(existingAttributes);

        if (allKeys.length !== Object.keys(newAttributes).length) return false;

        return allKeys.every((key) => existingAttributes[key] === newAttributes[key]);
      });

      if (existingIndex !== -1) {
        const updatedItems = [...prevItems];
        updatedItems[existingIndex] = {
          ...updatedItems[existingIndex],
          quantity: updatedItems[existingIndex].quantity + 1,
        };
        return updatedItems;
      }

      return [...prevItems, { ...newItem, quantity: 1 }];
    });
  };

  const increaseQuantity = (index: number): void => {
    const updated = [...cartItems];
    updated[index] = { ...updated[index], quantity: updated[index].quantity + 1 };
    setCartItems(updated);
  };

  const decreaseQuantity = (index: number): void => {
    const updated = [...cartItems];
    if (updated[index].quantity > 1) {
      updated[index] = { ...updated[index], quantity: updated[index].quantity - 1 };
      setCartItems(updated);
    } else {
      updated.splice(index, 1);
      setCartItems(updated);
    }
  };

  const updateAttribute = (index: number, attrName: string, value: string): void => {
    const updated = [...cartItems];
    updated[index] = {
      ...updated[index],
      selectedAttributes: { ...updated[index].selectedAttributes, [attrName]: value },
    };
    setCartItems(updated);
  };

  const clearCart = (): void => {
    setCartItems([]);
  };

  return {
    cartItems,
    addToCart,
    increaseQuantity,
    decreaseQuantity,
    updateAttribute,
    clearCart,
  };
};

export default useCart;
